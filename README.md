# IP Address Management System

A web-based IP address management system built with a microservice architecture.
## Tech Stack
- **Frontend:** Next.js (React), TypeScript, Tailwind CSS, shadcn/ui
- **Backend:** Laravel 12 (PHP 8.3)
- **Database:** MySQL 8.0
- **Auth:** JWT (via tymon/jwt-auth)
- **Containerization:** Docker

## Architecture
The system is composed of 4 containers:

```
Frontend (Next.js, port 3000)
Gateway Service (Laravel, port 8000)
Auth Service (port 8001)    
IP Management Service (port 8002)
```

## Getting Started

### Prerequisites

- Docker and Docker Compose installed

### Installation

1. Clone the repo:
```bash
git clone https://github.com/JeffAlteza/ip-management-system.git
cd ip-management-system
```

2. Build and start all containers:
```bash
docker compose up --build -d
```

This will start all services. The auth service will automatically wait for MySQL to be ready, run migrations, and seed default users. The IP management service does the same.

3. Open the app:
- **Frontend:** http://localhost:3000
- **Gateway API:** http://localhost:8000

### Default Users

The auth service seeds two users on startup:

Super Admin | `super_admin@example.com` | `password` |
Regular User | `user@example.com` | `password` |

## API Endpoints
All API requests go through the gateway at `http://localhost:8000/api`.

## Audit Log
Every change in the system is recorded in the audit log:
- IP created / updated / deleted
- User login / logout / registration

Each log entry stores: who did it (`user_id`), what changed (`old_values`, `new_values`), the action type, the session ID, and a timestamp. 
The audit log cannot be deleted by any user, including super admins.

## Running Tests
Each Laravel service has feature tests written with Pest:

```bash
# Auth service tests
docker compose exec auth-service php artisan test

# Gateway service tests
docker compose exec gateway php artisan test

# IP management service tests
docker compose exec ip-service php artisan test
```