const express = require('express');
const db = require('../config/database');
const { authenticateToken } = require('../middleware/auth');

const router = express.Router();

// All routes require authentication
router.use(authenticateToken);

// Create a new ticket
router.post('/', (req, res) => {
  const { title, description, priority } = req.body;
  const userId = req.user.id;
  const organization = req.user.organization;

  if (!title || !description) {
    return res.status(400).json({ error: 'Title and description are required' });
  }

  const ticketPriority = priority || 'medium';

  db.run(
    'INSERT INTO tickets (title, description, priority, created_by, organization) VALUES (?, ?, ?, ?, ?)',
    [title, description, ticketPriority, userId, organization],
    function (err) {
      if (err) {
        return res.status(500).json({ error: 'Failed to create ticket' });
      }
      res.status(201).json({
        message: 'Ticket created successfully',
        ticketId: this.lastID
      });
    }
  );
});

// Get all tickets (filtered by organization)
router.get('/', (req, res) => {
  const organization = req.user.organization;
  const { status, priority } = req.query;

  let query = 'SELECT t.*, u1.username as creator_name, u2.username as assignee_name FROM tickets t LEFT JOIN users u1 ON t.created_by = u1.id LEFT JOIN users u2 ON t.assigned_to = u2.id WHERE t.organization = ?';
  const params = [organization];

  if (status) {
    query += ' AND t.status = ?';
    params.push(status);
  }

  if (priority) {
    query += ' AND t.priority = ?';
    params.push(priority);
  }

  query += ' ORDER BY t.created_at DESC';

  db.all(query, params, (err, tickets) => {
    if (err) {
      return res.status(500).json({ error: 'Failed to fetch tickets' });
    }
    res.json({ tickets });
  });
});

// Get a specific ticket by ID
router.get('/:id', (req, res) => {
  const ticketId = req.params.id;
  const organization = req.user.organization;

  db.get(
    'SELECT t.*, u1.username as creator_name, u1.email as creator_email, u2.username as assignee_name, u2.email as assignee_email FROM tickets t LEFT JOIN users u1 ON t.created_by = u1.id LEFT JOIN users u2 ON t.assigned_to = u2.id WHERE t.id = ? AND t.organization = ?',
    [ticketId, organization],
    (err, ticket) => {
      if (err) {
        return res.status(500).json({ error: 'Failed to fetch ticket' });
      }
      if (!ticket) {
        return res.status(404).json({ error: 'Ticket not found' });
      }
      res.json({ ticket });
    }
  );
});

// Update ticket status
router.patch('/:id/status', (req, res) => {
  const ticketId = req.params.id;
  const { status } = req.body;
  const organization = req.user.organization;

  const validStatuses = ['open', 'in-progress', 'resolved', 'closed'];
  if (!status || !validStatuses.includes(status)) {
    return res.status(400).json({ error: 'Invalid status. Valid values: open, in-progress, resolved, closed' });
  }

  db.run(
    'UPDATE tickets SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ? AND organization = ?',
    [status, ticketId, organization],
    function (err) {
      if (err) {
        return res.status(500).json({ error: 'Failed to update ticket status' });
      }
      if (this.changes === 0) {
        return res.status(404).json({ error: 'Ticket not found' });
      }
      res.json({ message: 'Ticket status updated successfully' });
    }
  );
});

// Assign ticket to user
router.patch('/:id/assign', (req, res) => {
  const ticketId = req.params.id;
  const { assignedTo } = req.body;
  const organization = req.user.organization;

  if (!assignedTo) {
    return res.status(400).json({ error: 'assignedTo user ID is required' });
  }

  // Verify the assigned user exists and is in the same organization
  db.get('SELECT id FROM users WHERE id = ? AND organization = ?', [assignedTo, organization], (err, user) => {
    if (err) {
      return res.status(500).json({ error: 'Server error' });
    }
    if (!user) {
      return res.status(404).json({ error: 'User not found in organization' });
    }

    db.run(
      'UPDATE tickets SET assigned_to = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ? AND organization = ?',
      [assignedTo, ticketId, organization],
      function (err) {
        if (err) {
          return res.status(500).json({ error: 'Failed to assign ticket' });
        }
        if (this.changes === 0) {
          return res.status(404).json({ error: 'Ticket not found' });
        }
        res.json({ message: 'Ticket assigned successfully' });
      }
    );
  });
});

// Update ticket
router.put('/:id', (req, res) => {
  const ticketId = req.params.id;
  const { title, description, priority } = req.body;
  const organization = req.user.organization;

  if (!title || !description) {
    return res.status(400).json({ error: 'Title and description are required' });
  }

  db.run(
    'UPDATE tickets SET title = ?, description = ?, priority = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ? AND organization = ?',
    [title, description, priority, ticketId, organization],
    function (err) {
      if (err) {
        return res.status(500).json({ error: 'Failed to update ticket' });
      }
      if (this.changes === 0) {
        return res.status(404).json({ error: 'Ticket not found' });
      }
      res.json({ message: 'Ticket updated successfully' });
    }
  );
});

// Delete ticket
router.delete('/:id', (req, res) => {
  const ticketId = req.params.id;
  const organization = req.user.organization;
  const userRole = req.user.role;

  // Only admins and support staff can delete tickets
  if (userRole !== 'admin' && userRole !== 'support') {
    return res.status(403).json({ error: 'Only admins and support staff can delete tickets' });
  }

  db.run(
    'DELETE FROM tickets WHERE id = ? AND organization = ?',
    [ticketId, organization],
    function (err) {
      if (err) {
        return res.status(500).json({ error: 'Failed to delete ticket' });
      }
      if (this.changes === 0) {
        return res.status(404).json({ error: 'Ticket not found' });
      }
      res.json({ message: 'Ticket deleted successfully' });
    }
  );
});

// Add comment to ticket
router.post('/:id/comments', (req, res) => {
  const ticketId = req.params.id;
  const { comment } = req.body;
  const userId = req.user.id;
  const organization = req.user.organization;

  if (!comment) {
    return res.status(400).json({ error: 'Comment is required' });
  }

  // Verify ticket exists and belongs to organization
  db.get('SELECT id FROM tickets WHERE id = ? AND organization = ?', [ticketId, organization], (err, ticket) => {
    if (err) {
      return res.status(500).json({ error: 'Server error' });
    }
    if (!ticket) {
      return res.status(404).json({ error: 'Ticket not found' });
    }

    db.run(
      'INSERT INTO comments (ticket_id, user_id, comment) VALUES (?, ?, ?)',
      [ticketId, userId, comment],
      function (err) {
        if (err) {
          return res.status(500).json({ error: 'Failed to add comment' });
        }
        res.status(201).json({
          message: 'Comment added successfully',
          commentId: this.lastID
        });
      }
    );
  });
});

// Get comments for a ticket
router.get('/:id/comments', (req, res) => {
  const ticketId = req.params.id;
  const organization = req.user.organization;

  // Verify ticket exists and belongs to organization
  db.get('SELECT id FROM tickets WHERE id = ? AND organization = ?', [ticketId, organization], (err, ticket) => {
    if (err) {
      return res.status(500).json({ error: 'Server error' });
    }
    if (!ticket) {
      return res.status(404).json({ error: 'Ticket not found' });
    }

    db.all(
      'SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.ticket_id = ? ORDER BY c.created_at ASC',
      [ticketId],
      (err, comments) => {
        if (err) {
          return res.status(500).json({ error: 'Failed to fetch comments' });
        }
        res.json({ comments });
      }
    );
  });
});

module.exports = router;
