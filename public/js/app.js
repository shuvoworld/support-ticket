// Global variables
let authToken = null;
let currentUser = null;

// Check if user is already logged in
window.addEventListener('DOMContentLoaded', () => {
    const token = localStorage.getItem('authToken');
    const user = localStorage.getItem('currentUser');
    
    if (token && user) {
        authToken = token;
        currentUser = JSON.parse(user);
        showMainSection();
        loadTickets();
    }
});

// Auth functions
function showLogin() {
    document.getElementById('login-tab').classList.add('active');
    document.getElementById('register-tab').classList.remove('active');
    document.getElementById('login-form').style.display = 'block';
    document.getElementById('register-form').style.display = 'none';
}

function showRegister() {
    document.getElementById('register-tab').classList.add('active');
    document.getElementById('login-tab').classList.remove('active');
    document.getElementById('register-form').style.display = 'block';
    document.getElementById('login-form').style.display = 'none';
}

async function handleLogin(event) {
    event.preventDefault();
    
    const username = document.getElementById('login-username').value;
    const password = document.getElementById('login-password').value;
    
    try {
        const response = await fetch('/api/auth/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username, password })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            authToken = data.token;
            currentUser = data.user;
            localStorage.setItem('authToken', authToken);
            localStorage.setItem('currentUser', JSON.stringify(currentUser));
            showMessage('auth-message', 'Login successful!', 'success');
            setTimeout(() => {
                showMainSection();
                loadTickets();
            }, 1000);
        } else {
            showMessage('auth-message', data.error || 'Login failed', 'error');
        }
    } catch (error) {
        showMessage('auth-message', 'Network error. Please try again.', 'error');
    }
}

async function handleRegister(event) {
    event.preventDefault();
    
    const username = document.getElementById('register-username').value;
    const email = document.getElementById('register-email').value;
    const password = document.getElementById('register-password').value;
    const organization = document.getElementById('register-organization').value;
    const role = document.getElementById('register-role').value;
    
    try {
        const response = await fetch('/api/auth/register', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username, email, password, organization, role })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            showMessage('auth-message', 'Registration successful! Please login.', 'success');
            setTimeout(() => {
                showLogin();
            }, 2000);
        } else {
            showMessage('auth-message', data.error || 'Registration failed', 'error');
        }
    } catch (error) {
        showMessage('auth-message', 'Network error. Please try again.', 'error');
    }
}

function handleLogout() {
    authToken = null;
    currentUser = null;
    localStorage.removeItem('authToken');
    localStorage.removeItem('currentUser');
    showAuthSection();
}

function showAuthSection() {
    document.getElementById('auth-section').style.display = 'flex';
    document.getElementById('main-section').style.display = 'none';
}

function showMainSection() {
    document.getElementById('auth-section').style.display = 'none';
    document.getElementById('main-section').style.display = 'block';
    document.getElementById('user-info').textContent = `${currentUser.username} (${currentUser.organization})`;
}

// Ticket functions
async function loadTickets() {
    const status = document.getElementById('filter-status').value;
    const priority = document.getElementById('filter-priority').value;
    
    let url = '/api/tickets?';
    if (status) url += `status=${status}&`;
    if (priority) url += `priority=${priority}&`;
    
    try {
        const response = await fetch(url, {
            headers: { 
                'Authorization': `Bearer ${authToken}`
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            displayTickets(data.tickets);
        } else {
            showMessage('main-message', data.error || 'Failed to load tickets', 'error');
        }
    } catch (error) {
        showMessage('main-message', 'Network error. Please try again.', 'error');
    }
}

function displayTickets(tickets) {
    const ticketsList = document.getElementById('tickets-list');
    
    if (tickets.length === 0) {
        ticketsList.innerHTML = '<p style="text-align: center; color: #95a5a6; padding: 40px;">No tickets found.</p>';
        return;
    }
    
    ticketsList.innerHTML = tickets.map(ticket => `
        <div class="ticket-card" onclick="viewTicket(${ticket.id})">
            <div class="ticket-header">
                <div class="ticket-title">#${ticket.id} - ${escapeHtml(ticket.title)}</div>
            </div>
            <div class="ticket-meta">
                <span class="badge badge-status ${ticket.status}">${ticket.status}</span>
                <span class="badge badge-priority ${ticket.priority}">${ticket.priority}</span>
            </div>
            <div class="ticket-description">${escapeHtml(ticket.description)}</div>
            <div class="ticket-footer">
                <span>Created by: ${escapeHtml(ticket.creator_name)}</span>
                <span>${ticket.assignee_name ? 'Assigned to: ' + escapeHtml(ticket.assignee_name) : 'Unassigned'}</span>
                <span>${new Date(ticket.created_at).toLocaleDateString()}</span>
            </div>
        </div>
    `).join('');
}

async function viewTicket(ticketId) {
    try {
        const response = await fetch(`/api/tickets/${ticketId}`, {
            headers: { 
                'Authorization': `Bearer ${authToken}`
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            displayTicketDetails(data.ticket);
            loadTicketComments(ticketId);
        } else {
            showMessage('main-message', data.error || 'Failed to load ticket', 'error');
        }
    } catch (error) {
        showMessage('main-message', 'Network error. Please try again.', 'error');
    }
}

async function loadTicketComments(ticketId) {
    try {
        const response = await fetch(`/api/tickets/${ticketId}/comments`, {
            headers: { 
                'Authorization': `Bearer ${authToken}`
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            displayComments(data.comments, ticketId);
        }
    } catch (error) {
        console.error('Failed to load comments', error);
    }
}

function displayTicketDetails(ticket) {
    const modal = document.getElementById('ticket-details-modal');
    const content = document.getElementById('ticket-details-content');
    
    content.innerHTML = `
        <div class="ticket-details">
            <h2>#${ticket.id} - ${escapeHtml(ticket.title)}</h2>
            
            <div class="ticket-meta" style="margin: 15px 0;">
                <span class="badge badge-status ${ticket.status}">${ticket.status}</span>
                <span class="badge badge-priority ${ticket.priority}">${ticket.priority}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Description:</span>
                <span class="detail-value">${escapeHtml(ticket.description)}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Created By:</span>
                <span class="detail-value">${escapeHtml(ticket.creator_name)} (${escapeHtml(ticket.creator_email)})</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Assigned To:</span>
                <span class="detail-value">${ticket.assignee_name ? escapeHtml(ticket.assignee_name) + ' (' + escapeHtml(ticket.assignee_email) + ')' : 'Unassigned'}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Created:</span>
                <span class="detail-value">${new Date(ticket.created_at).toLocaleString()}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Last Updated:</span>
                <span class="detail-value">${new Date(ticket.updated_at).toLocaleString()}</span>
            </div>
            
            <div class="ticket-actions">
                <button onclick="changeTicketStatus(${ticket.id}, 'open')" class="btn btn-primary btn-small">Mark Open</button>
                <button onclick="changeTicketStatus(${ticket.id}, 'in-progress')" class="btn btn-secondary btn-small">In Progress</button>
                <button onclick="changeTicketStatus(${ticket.id}, 'resolved')" class="btn btn-success btn-small">Resolved</button>
                <button onclick="changeTicketStatus(${ticket.id}, 'closed')" class="btn btn-secondary btn-small">Close</button>
            </div>
            
            <div class="comments-section" id="comments-section">
                <h3>Comments</h3>
                <div id="comments-list"></div>
                
                <div class="comment-form">
                    <textarea id="new-comment" placeholder="Add a comment..." rows="3"></textarea>
                    <button onclick="addComment(${ticket.id})" class="btn btn-primary btn-small">Add Comment</button>
                </div>
            </div>
        </div>
    `;
    
    modal.style.display = 'block';
}

function displayComments(comments, ticketId) {
    const commentsList = document.getElementById('comments-list');
    
    if (comments.length === 0) {
        commentsList.innerHTML = '<p style="color: #95a5a6;">No comments yet.</p>';
        return;
    }
    
    commentsList.innerHTML = comments.map(comment => `
        <div class="comment">
            <div class="comment-header">
                <span class="comment-author">${escapeHtml(comment.username)}</span>
                <span class="comment-date">${new Date(comment.created_at).toLocaleString()}</span>
            </div>
            <div class="comment-text">${escapeHtml(comment.comment)}</div>
        </div>
    `).join('');
}

async function addComment(ticketId) {
    const commentText = document.getElementById('new-comment').value.trim();
    
    if (!commentText) {
        alert('Please enter a comment');
        return;
    }
    
    try {
        const response = await fetch(`/api/tickets/${ticketId}/comments`, {
            method: 'POST',
            headers: { 
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ comment: commentText })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            document.getElementById('new-comment').value = '';
            loadTicketComments(ticketId);
        } else {
            alert(data.error || 'Failed to add comment');
        }
    } catch (error) {
        alert('Network error. Please try again.');
    }
}

async function changeTicketStatus(ticketId, status) {
    try {
        const response = await fetch(`/api/tickets/${ticketId}/status`, {
            method: 'PATCH',
            headers: { 
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            hideTicketDetails();
            loadTickets();
            showMessage('main-message', 'Ticket status updated successfully', 'success');
            setTimeout(() => hideMessage('main-message'), 3000);
        } else {
            alert(data.error || 'Failed to update status');
        }
    } catch (error) {
        alert('Network error. Please try again.');
    }
}

function hideTicketDetails() {
    document.getElementById('ticket-details-modal').style.display = 'none';
}

function showCreateTicketForm() {
    document.getElementById('create-ticket-form').style.display = 'block';
}

function hideCreateTicketForm() {
    document.getElementById('create-ticket-form').style.display = 'none';
    document.getElementById('ticket-title').value = '';
    document.getElementById('ticket-description').value = '';
    document.getElementById('ticket-priority').value = 'medium';
}

async function handleCreateTicket(event) {
    event.preventDefault();
    
    const title = document.getElementById('ticket-title').value;
    const description = document.getElementById('ticket-description').value;
    const priority = document.getElementById('ticket-priority').value;
    
    try {
        const response = await fetch('/api/tickets', {
            method: 'POST',
            headers: { 
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ title, description, priority })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            hideCreateTicketForm();
            loadTickets();
            showMessage('main-message', 'Ticket created successfully', 'success');
            setTimeout(() => hideMessage('main-message'), 3000);
        } else {
            showMessage('main-message', data.error || 'Failed to create ticket', 'error');
        }
    } catch (error) {
        showMessage('main-message', 'Network error. Please try again.', 'error');
    }
}

// Utility functions
function showMessage(elementId, message, type) {
    const element = document.getElementById(elementId);
    element.textContent = message;
    element.className = `message ${type}`;
}

function hideMessage(elementId) {
    const element = document.getElementById(elementId);
    element.style.display = 'none';
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Close modals when clicking outside
window.onclick = function(event) {
    const createModal = document.getElementById('create-ticket-form');
    const detailsModal = document.getElementById('ticket-details-modal');
    
    if (event.target === createModal) {
        hideCreateTicketForm();
    }
    if (event.target === detailsModal) {
        hideTicketDetails();
    }
}
