# Workcity Client Project Manager WordPress Plugin

A comprehensive WordPress plugin for managing client projects with custom post types, meta fields, and shortcode display functionality, built according to specific requirements.

## Features

- **Custom Post Type**: "Client Project" with slug `client_project`
- **Meta Fields**: 
  - Project Title (WordPress post title)
  - Client Name (text input)
  - Project Description (WordPress editor)
  - Status (dropdown: Not Started, In Progress, Completed, On Hold)
  - Deadline (HTML5 date input)
- **Shortcode Display**: `[display_projects]` to show all published projects
- **Security**: Proper nonce validation and data sanitization
- **Coding Standards**: All functions prefixed with `wcp_`

## Installation

1. Upload the plugin folder to `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Start creating client projects from the WordPress admin menu

## Usage

### Creating Client Projects

1. Go to **Client Projects > Add New** in your WordPress admin
2. Enter the project title and description using the standard WordPress fields
3. Fill in the "Project Details" meta box:
   - **Client Name**: Enter the client's name
   - **Status**: Select from: Not Started, In Progress, Completed, On Hold
   - **Deadline**: Set the project deadline using the date picker
4. Publish the project

### Displaying Projects with Shortcode

Use the `[display_projects]` shortcode to display all published client projects on any page or post.

#### Basic Usage
```
[display_projects]
```

The shortcode will display all published client projects with the following information for each:
- Project Title
- Client Name
- Status
- Deadline
- Description (from the WordPress editor)

#### Example Output

Each project is displayed as a card containing:
- **Project Title** as a heading
- **Client Name**: The name of the client
- **Status**: Current project status with color-coded badge
- **Deadline**: Formatted deadline date
- **Description**: Full project description from the editor

## Technical Details

### Custom Post Type
- **Slug**: `client_project`
- **Supports**: Title and Editor only (as required)
- **Public**: Yes, with archive pages
- **Menu Icon**: Portfolio icon

### Meta Fields
All meta fields are stored with the `_wcp_` prefix:
- `_wcp_client_name`: Client name (text)
- `_wcp_project_status`: Project status (dropdown)
- `_wcp_project_deadline`: Project deadline (date)

### Security Features
- Nonce validation for all form submissions
- Capability checks for user permissions
- Input sanitization and output escaping
- Validation of status values and date formats

### Function Prefixes
All custom functions are prefixed with `wcp_` to prevent conflicts:
- `wcp_register_client_project_post_type()`
- `wcp_add_project_meta_boxes()`
- `wcp_project_details_callback()`
- `wcp_save_project_meta_fields()`
- `wcp_display_projects_shortcode()`
- `wcp_enqueue_styles()`

## File Structure

```
workcity-client-project-manager/
├── client-projects-manager.php    # Main plugin file
├── assets/
│   └── style.css                  # Plugin styles
├── README.md                      # Documentation
├── single-client_project.php      # Single project template (optional)
└── archive-client_project.php     # Archive template (optional)
```

## Styling

The plugin includes comprehensive CSS styling with:
- Responsive design for all screen sizes
- Status-based color coding
- Professional card layout
- Print-friendly styles
- Admin form styling

### CSS Classes
- `.wcp-projects-list`: Main container
- `.wcp-project-item`: Individual project container
- `.wcp-project-title`: Project title
- `.wcp-project-meta`: Meta information container
- `.wcp-status-badge`: Status badge with color coding
- `.wcp-project-description`: Description content
- `.wcp-no-projects`: No projects message

## Status Options

The plugin includes four status options as required:
1. **Not Started** - Gray badge
2. **In Progress** - Orange badge  
3. **Completed** - Green badge
4. **On Hold** - Red badge

## Requirements Compliance

✅ Custom Post Type with slug `client_project`  
✅ Public-facing names: "Client Project" (singular) and "Client Projects" (plural)  
✅ Supports Title and Editor fields only  
✅ Meta box titled "Project Details"  
✅ Client Name text input field  
✅ Status dropdown with exact options: "Not Started", "In Progress", "Completed", "On Hold"  
✅ Deadline HTML5 date input field  
✅ Secure data saving with nonce validation  
✅ Shortcode named `[display_projects]`  
✅ Displays all published projects with all information  
✅ All functions prefixed with `wcp_`  
✅ Input sanitization and output escaping  
✅ Comprehensive code comments  

## WordPress Version Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher

## Support

For customization and support, refer to the WordPress Codex and plugin development documentation.

## License

This plugin is licensed under the GPL v2 or later.
