# Workcity Client Project Manager

A comprehensive WordPress plugin for managing client projects with advanced features including progress tracking, budget management, client contact information, and project categorization.

## Features

### Core Functionality
- **Custom Post Type**: `client_project` for managing client projects
- **Project Categories**: Organize projects with custom taxonomy
- **Advanced Meta Fields**: 
  - Client Name
  - Project Status (Not Started, In Progress, Completed, On Hold)
  - Project Deadline
  - Progress Percentage with visual progress bars
  - Project Description

### Client Management
- **Client Contact Information**:
  - Client Email Address
  - Client Phone Number
  - Client Company Name

### Budget & Financial Tracking
- **Project Budget**: Set and track project budgets
- **Currency Support**: Multiple currency options (USD, EUR, GBP, CAD, AUD)
- **Budget Status**: Visual indicators for budget tracking

### Display & Filtering
- **Flexible Shortcode**: `[display_projects]` with multiple options
- **Grid and List Views**: Choose between card grid or detailed list display
- **Advanced Filtering**: Filter by category, status, or client
- **Visual Progress Indicators**: Progress bars and status badges
- **Deadline Alerts**: Visual warnings for approaching and overdue deadlines

### Admin Features
- **Dashboard Widget**: Project statistics and overdue alerts
- **Organized Meta Boxes**: Separated into logical groups (Details, Client Info, Budget)
- **User-Friendly Interface**: Clean, responsive admin interface
- **Security**: Nonce validation and input sanitization throughout

## Installation

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Start creating client projects under "Client Projects" in your admin menu

## Usage

### Creating Projects
1. Navigate to "Client Projects" → "Add New" in your WordPress admin
2. Fill in project details across the three meta boxes:
   - **Project Details**: Name, status, deadline, progress, description
   - **Client Information**: Contact details and company information
   - **Budget & Pricing**: Budget amount and currency selection
3. Assign project categories as needed
4. Publish the project

### Displaying Projects

Use the shortcode `[display_projects]` with these optional attributes:

#### Basic Usage
```
[display_projects]
```

#### Advanced Options
```
[display_projects category="web-development" status="in_progress" client="john-doe" layout="grid" show_progress="true" show_budget="false" orderby="deadline" order="ASC"]
```

#### Shortcode Attributes

| Attribute | Description | Default | Options |
|-----------|-------------|---------|---------|
| `category` | Filter by project category | none | Category slug |
| `status` | Filter by project status | none | `not_started`, `in_progress`, `completed`, `on_hold` |
| `client` | Filter by client name | none | Client name (partial match) |
| `layout` | Display layout | `grid` | `grid`, `list` |
| `show_progress` | Show progress bars | `true` | `true`, `false` |
| `show_budget` | Show budget information | `false` | `true`, `false` |
| `orderby` | Sort projects by | `date` | `date`, `title`, `deadline`, `progress` |
| `order` | Sort order | `DESC` | `ASC`, `DESC` |
| `limit` | Number of projects to show | `-1` (all) | Any positive number |

### Examples

**Show only completed projects:**
```
[display_projects status="completed"]
```

**Show web development projects in list view:**
```
[display_projects category="web-development" layout="list"]
```

**Show projects sorted by deadline:**
```
[display_projects orderby="deadline" order="ASC"]
```

**Show projects with budget information:**
```
[display_projects show_budget="true"]
```

## Project Categories

The plugin includes a custom taxonomy for organizing projects:
- Create categories in "Client Projects" → "Categories"
- Assign multiple categories to each project
- Filter projects by category in the shortcode
- Categories display as tags on project cards

## Dashboard Widget

The admin dashboard includes a "Project Overview" widget showing:
- Total number of projects by status
- Overdue project alerts with direct links
- Quick project statistics
- Visual progress indicators
## Security Features

- Nonce verification for all form submissions
- Input sanitization and validation
- Output escaping for safe display
- Capability checks for admin functions
- SQL injection prevention through WordPress APIs

## Styling

The plugin includes comprehensive CSS styling:
- Responsive design for all screen sizes
- Status-based color coding
- Hover effects and animations
- Progress bar visualizations
- Print-friendly styles
- Customizable through theme CSS overrides

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- Modern web browser for optimal admin experience

## Plugin Structure

```
workcity-client-project-manager/
├── client-projects-manager.php    # Main plugin file
├── assets/
│   └── style.css                 # Frontend and admin styles
├── templates/
│   ├── single-client_project.php  # Single project template
│   └── archive-client_project.php # Project archive template
└── README.md                     # Documentation
```

## Customization

### Custom Templates
Override the default templates by copying them to your theme:
- `single-client_project.php` - Single project page
- `archive-client_project.php` - Project archive page

### Custom Styling
Add custom CSS to your theme to modify the appearance:
```css
.wcp-project-item {
    /* Your custom styles */
}
```

### Hooks and Filters
The plugin provides several hooks for developers:
- `wcp_project_meta_fields` - Modify meta field saving
- `wcp_shortcode_query_args` - Modify shortcode query arguments
- `wcp_project_display_html` - Modify project display output

## Technical Details

### Custom Post Type
- **Slug**: `client_project`
- **Supports**: Title and Editor
- **Public**: Yes, with archive pages
- **Menu Icon**: Portfolio icon

### Meta Fields
All meta fields are stored with the `_wcp_` prefix:
- `_wcp_client_name`: Client name
- `_wcp_project_status`: Project status
- `_wcp_project_deadline`: Project deadline
- `_wcp_project_progress`: Progress percentage
- `_wcp_client_email`: Client email
- `_wcp_client_phone`: Client phone
- `_wcp_client_company`: Client company
- `_wcp_project_budget`: Project budget
- `_wcp_project_currency`: Budget currency

### Function Prefixes
All custom functions are prefixed with `wcp_`:
- `wcp_register_client_project_post_type()`
- `wcp_register_project_categories()`
- `wcp_add_project_meta_boxes()`
- `wcp_save_project_meta_fields()`
- `wcp_display_projects_shortcode()`
- `wcp_add_dashboard_widget()`

## Requirements Compliance

✅ Custom Post Type with slug `client_project`  
✅ Public-facing names: "Client Project" and "Client Projects"  
✅ Meta boxes with required fields  
✅ Secure data saving with nonce validation  
✅ Shortcode named `[display_projects]`  
✅ All functions prefixed with `wcp_`  
✅ Input sanitization and output escaping  
✅ Comprehensive functionality and features  

## Support

For support and feature requests, please visit the plugin's GitHub repository or contact the development team.

## Version History

- **1.2.0** - Enhanced functionality with progress tracking, categories, budget management, and dashboard widget
- **1.1.0** - Added client contact information and improved admin interface  
- **1.0.0** - Initial release with core project management features

## License

This plugin is licensed under the GPL v2 or later.
