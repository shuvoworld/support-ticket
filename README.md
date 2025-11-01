# Support Ticket Platform

A cloud-based support ticket platform for organizations - small but effective.

## Features

- **User Authentication**: Secure registration and login with JWT tokens
- **Ticket Management**: Create, view, update, and delete support tickets
- **Ticket Status Tracking**: Open, In-Progress, Resolved, and Closed statuses
- **Priority Levels**: Low, Medium, High, and Urgent priority assignment
- **Comments System**: Add comments to tickets for collaboration
- **Ticket Assignment**: Assign tickets to support staff
- **Organization-based**: Multi-tenant support with organization isolation
- **Role-based Access**: User, Support Staff, and Admin roles
- **Responsive UI**: Clean, modern web interface that works on all devices
- **RESTful API**: Well-documented API for integration

## Technology Stack

- **Backend**: Node.js with Express.js
- **Database**: SQLite (easily portable to cloud databases)
- **Authentication**: JWT (JSON Web Tokens)
- **Frontend**: Vanilla JavaScript, HTML5, CSS3

## Installation

1. Clone the repository:
```bash
git clone https://github.com/shuvoworld/support-ticket.git
cd support-ticket
```

2. Install dependencies:
```bash
npm install
```

3. Create environment configuration:
```bash
cp .env.example .env
```

4. Edit `.env` file and set your configuration:
```
PORT=3000
JWT_SECRET=your-secure-secret-key
NODE_ENV=production
```

5. Create data directory:
```bash
mkdir data
```

## Usage

### Starting the Server

```bash
npm start
```

The application will be available at `http://localhost:3000`

### First-time Setup

1. Open your browser and navigate to `http://localhost:3000`
2. Register a new user account
3. Select your role (User, Support Staff, or Admin)
4. Login with your credentials

## API Documentation

### Authentication Endpoints

#### Register User
```http
POST /api/auth/register
Content-Type: application/json

{
  "username": "john_doe",
  "email": "john@example.com",
  "password": "secure_password",
  "organization": "Acme Corp",
  "role": "user"
}
```

#### Login
```http
POST /api/auth/login
Content-Type: application/json

{
  "username": "john_doe",
  "password": "secure_password"
}
```

### Ticket Endpoints (Requires Authentication)

All ticket endpoints require the `Authorization: Bearer <token>` header.

#### Create Ticket
```http
POST /api/tickets
Authorization: Bearer <token>
Content-Type: application/json

{
  "title": "Login issue",
  "description": "Cannot login to the system",
  "priority": "high"
}
```

#### Get All Tickets
```http
GET /api/tickets?status=open&priority=high
Authorization: Bearer <token>
```

#### Get Ticket by ID
```http
GET /api/tickets/:id
Authorization: Bearer <token>
```

#### Update Ticket Status
```http
PATCH /api/tickets/:id/status
Authorization: Bearer <token>
Content-Type: application/json

{
  "status": "in-progress"
}
```

#### Assign Ticket
```http
PATCH /api/tickets/:id/assign
Authorization: Bearer <token>
Content-Type: application/json

{
  "assignedTo": 2
}
```

#### Update Ticket
```http
PUT /api/tickets/:id
Authorization: Bearer <token>
Content-Type: application/json

{
  "title": "Updated title",
  "description": "Updated description",
  "priority": "medium"
}
```

#### Delete Ticket (Admin/Support only)
```http
DELETE /api/tickets/:id
Authorization: Bearer <token>
```

#### Add Comment to Ticket
```http
POST /api/tickets/:id/comments
Authorization: Bearer <token>
Content-Type: application/json

{
  "comment": "This is a comment"
}
```

#### Get Ticket Comments
```http
GET /api/tickets/:id/comments
Authorization: Bearer <token>
```

### Health Check
```http
GET /api/health
```

## User Roles

- **User**: Can create tickets, view their own tickets, add comments
- **Support**: All user permissions plus can view all tickets, assign tickets, update status
- **Admin**: All support permissions plus can delete tickets

## Database Schema

### Users Table
- id (PRIMARY KEY)
- username (UNIQUE)
- email (UNIQUE)
- password (hashed)
- role (user/support/admin)
- organization
- created_at

### Tickets Table
- id (PRIMARY KEY)
- title
- description
- status (open/in-progress/resolved/closed)
- priority (low/medium/high/urgent)
- created_by (FOREIGN KEY -> users.id)
- assigned_to (FOREIGN KEY -> users.id)
- organization
- created_at
- updated_at

### Comments Table
- id (PRIMARY KEY)
- ticket_id (FOREIGN KEY -> tickets.id)
- user_id (FOREIGN KEY -> users.id)
- comment
- created_at

## Deployment

### Cloud Deployment Options

This application can be easily deployed to:

- **Heroku**: Add Heroku Postgres addon and update database configuration
- **AWS**: Deploy to EC2, Elastic Beanstalk, or Lambda with API Gateway
- **Google Cloud**: Deploy to App Engine or Cloud Run
- **Azure**: Deploy to App Service

### Database Migration for Production

For production use, migrate from SQLite to a cloud database:

1. Install appropriate database driver (e.g., `pg` for PostgreSQL)
2. Update `src/config/database.js` with cloud database connection
3. Run schema migration

## Security Features

✅ **Implemented Security Measures:**
- JWT authentication with secure token management
- Password hashing using bcryptjs (10 rounds)
- Rate limiting on all API endpoints (100 requests per 15 minutes)
- Stricter rate limiting on authentication endpoints (10 attempts per 15 minutes)
- SQL injection prevention through parameterized queries
- Organization-based data isolation
- Role-based access control
- Input validation and sanitization
- Production environment checks for JWT_SECRET

⚠️ **Additional Security Considerations for Production:**
- Change `JWT_SECRET` to a strong, random value (enforced in production)
- Use HTTPS/TLS in production
- Regular security updates for dependencies
- Consider adding CORS whitelist for production
- Implement additional logging and monitoring
- Add request size limits
- Consider adding helmet.js for additional HTTP security headers

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

ISC

## Support

For issues and questions, please create an issue in the GitHub repository.