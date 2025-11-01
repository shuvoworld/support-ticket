# Support Ticket System

A modern, feature-rich support ticket management system built with Laravel and Filament, designed to streamline customer support workflows with elegant UI and powerful functionality.

## 🌟 Features

### 🎫 Core Ticket Management
- **Multi-role Support**: Super Admin, Admin, Agent, and User roles with granular permissions
- **Ticket Lifecycle**: Complete ticket workflow from creation to resolution
- **Priority Levels**: Low, Medium, High, and Urgent priority classification
- **Categories & Departments**: Organize tickets by type and assign to appropriate teams
- **Status Tracking**: Open, In Progress, and Closed status management
- **Auto-assignment**: Tickets automatically assign to first viewing agent

### 💬 Communication System
- **Real-time Comments**: Add comments without page refresh using Livewire
- **Internal Notes**: Private agent communications not visible to customers
- **Threaded Conversations**: Organized comment history with user avatars
- **Smart Status Updates**: Automatic progression when agents respond to tickets
- **Role-based Visibility**: Different comment visibility for users vs agents

### 🎨 User Interface
- **Dual Interface System**: Separate panels for Admin/Agent and User experiences
- **Filament Admin Panel**: Professional admin interface for ticket management
- **Filament User Panel**: Clean interface for customers to manage their tickets
- **Traditional Web Interface**: Responsive web pages for ticket creation and viewing
- **Modern Design**: Glass morphism effects, smooth animations, and responsive layouts

### 🚀 Advanced Features
- **Smart Actions**: Quick assign, close, reopen, and escalate ticket operations
- **Auto-save Drafts**: Prevent data loss with automatic draft saving
- **Character Counters**: Real-time feedback on input lengths
- **Search & Filter**: Advanced ticket search with multiple criteria
- **Bulk Operations**: Efficient ticket management at scale
- **Email Notifications**: Stay informed about ticket updates

### 🔐 Security & Permissions
- **Role-based Access Control**: Granular permissions for different user types
- **Department-based Filtering**: Agents only see tickets from their assigned departments
- **Secure Comment System**: Internal notes protected from customer view
- **Audit Trail**: Complete history of all ticket activities

## 🏗️ Architecture

### Backend Technologies
- **Laravel 11**: Modern PHP framework with robust features
- **Filament 3**: Elegant admin panel framework
- **Livewire**: Real-time UI updates without page refresh
- **Tailwind CSS**: Utility-first CSS framework
- **MySQL**: Relational database for data storage

### Frontend Technologies
- **Blade Templates**: Laravel's templating engine
- **Tailwind CSS**: Responsive design system
- **JavaScript**: Dynamic interactions and animations
- **Heroicons**: Consistent icon library

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- Composer
- MySQL/MariaDB
- Node.js (for frontend assets)

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd support-ticket
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   npm run build
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Start the application**
   ```bash
   php artisan serve
   ```

6. **Visit the application**
   - Main Application: `http://localhost:8000`
   - Admin Panel: `http://localhost:8000/admin`
   - User Panel: `http://localhost:8000/user`

## 👥 Demo Users

### Super Admin
- **Email**: `admin@example.com`
- **Password**: `password`
- **Access**: Full system access including user management

### Admin
- **Email**: `manager@example.com`
- **Password**: `password`
- **Access**: Ticket management and system configuration

### Agent
- **Email**: `agent@example.com`
- **Password**: `password`
- **Access**: Handle assigned tickets and respond to customers

### Regular User
- **Email**: `user@example.com`
- **Password**: `password`
- **Access**: Create and manage own tickets

## 📋 Access Points

### User Interfaces
1. **Main Web Interface** (`/`)
   - Landing page and public access
   - Ticket creation: `/tickets/create`
   - User dashboard: `/dashboard`
   - My tickets: `/tickets`

2. **Admin Panel** (`/admin`)
   - Complete ticket management system
   - User management and permissions
   - System configuration and reporting
   - Advanced filtering and analytics

3. **User Panel** (`/user`)
   - Personal ticket management
   - Clean, customer-focused interface
   - Simplified ticket creation and tracking

## 🎯 Key Workflows

### For Customers
1. **Create Ticket**: Fill out the ticket creation form with issue details
2. **Track Progress**: View ticket status and communicate with support team
3. **Receive Updates**: Get notifications about ticket progress
4. **Close Tickets**: Mark issues as resolved when satisfied

### For Agents
1. **View Assigned Tickets**: See tickets from assigned departments
2. **Auto-assignment**: Tickets automatically assign when first viewed
3. **Respond to Customers**: Add comments and internal notes
4. **Status Management**: Update ticket status through workflow
5. **Escalate Issues**: Raise priority for urgent matters

### For Administrators
1. **System Oversight**: Monitor all tickets and agent performance
2. **User Management**: Create and manage user accounts
3. **Department Configuration**: Organize support teams
4. **Advanced Analytics**: Track performance metrics
5. **System Settings**: Configure system behavior

## 🔧 Customization

### Adding New Roles
1. Define roles in `database/seeders/RoleSeeder.php`
2. Set permissions in `app/Providers/AuthServiceProvider.php`
3. Update role checks throughout the application

### Custom Ticket Fields
1. Modify `database/migrations/` files for new database columns
2. Update forms in `app/Filament/Resources/Tickets/Schemas/TicketForm.php`
3. Adjust views in `resources/views/tickets/`

### Email Templates
1. Edit templates in `resources/views/emails/`
2. Configure notifications in `app/Notifications/`

## 📊 Features in Detail

### Ticket Creation
- **Rich Form Interface**: Comprehensive ticket creation with all necessary fields
- **Category Selection**: Organize tickets by issue type
- **Priority Classification**: Define urgency levels
- **Department Assignment**: Route tickets to appropriate teams
- **Auto-save Functionality**: Prevent data loss during form completion

### Agent Dashboard
- **Dynamic Comment System**: Add comments in real-time without page refresh
- **Smart Actions Panel**: Quick access to common ticket operations
- **Auto-assignment Logic**: Automatically assign unassigned tickets
- **Internal Collaboration**: Private notes for team coordination
- **Status Automation**: Intelligent status progression based on actions

### User Experience
- **Responsive Design**: Works perfectly on desktop, tablet, and mobile
- **Modern UI/UX**: Glass morphism effects and smooth animations
- **Intuitive Navigation**: Clear structure and easy-to-find features
- **Real-time Updates**: See changes instantly without page refresh
- **Accessibility**: WCAG compliant design patterns

## 🛠️ Development

### File Structure
```
├── app/
│   ├── Filament/
│   │   ├── Resources/Tickets/     # Admin panel resources
│   │   ├── User/Resources/Tickets/ # User panel resources
│   │   └── Widgets/               # Custom Livewire widgets
│   ├── Http/Controllers/          # Web controllers
│   ├── Models/                    # Eloquent models
│   └── Notifications/             # System notifications
├── database/
│   ├── migrations/                # Database migrations
│   ├── seeders/                   # Database seeders
│   └── factories/                 # Model factories
├── resources/
│   ├── views/                     # Blade templates
│   └── filament/                  # Filament custom views
└── routes/                        # Application routes
```

### Contributing
1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🤝 Support

If you encounter any issues or have questions:
1. Check the existing documentation
2. Search for similar issues in the repository
3. Create a new issue with detailed information
4. Contact the development team

## 🎉 Acknowledgments

- **Laravel Team** - For the amazing PHP framework
- **Filament Team** - For the elegant admin panel framework
- **Tailwind CSS** - For the utility-first CSS framework
- **Heroicons** - For the beautiful icon library
- **Livewire Team** - For the dynamic PHP framework

---

**Built with ❤️ for efficient customer support management**