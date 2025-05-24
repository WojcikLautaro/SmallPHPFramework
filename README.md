# SmallPHPFramework
This is a lightweight PHP routing and middleware framework built from scratch as part of my portfolio. It features:

## Requirements
- PHP 8.1+
  
## Features
- Custom routing (GET/POST)
- Middleware support
- Simple templating engine
- Error handling (404, 500)
- JSON & HTML responses
- Route handling with middleware support
- Pre/post middleware chaining
- Basic controller dispatch pattern
- Response abstraction (JSON, view rendering, redirects)
- Minimal and clean architecture
  
##Structure 
SmallPHPFramework/
│
├── app/                        # Application-specific logic
│   ├── Controllers/            # Controller classes
│   ├── Middleware/             # Middleware classes
│   └── Models/                 # Domain models (if needed)
│
├── core/                       # Framework core
│   ├── Controller.php          # Base Controller class
│   ├── Middleware.php          # Middleware contract
│   ├── Route.php               # Routing definitions
│   ├── RouteHandler.php        # Resolves controller + middleware
│   ├── Request.php             # HTTP Request wrapper
│   ├── Response.php            # Response abstraction
│   ├── View.php                # Template renderer
│   └── ConfigManager.php       # Configuration manager
│
├── public/                     # Publicly accessible directory
│   └── index.php               # Entry point
│
├── resources/                  # Templates
│   └── views/                  # Templates
│
├── routes/                     # Route definition files
│   └── web.php                 # Main route file
│
├── logs/                       # Application logs
│
└── config/                     # Configuration settings
