# Application Architecture Guide: Controller, Service, Repository

This application uses a three-layered architecture to ensure a clear separation of concerns:

## 1. Controller (app/Http/Controllers/)
- **Role:** Entry point for API requests.
- **Responsibilities:** Request mapping, input validation (simple), and response formatting.
- **Constraint:** Must only delegate to the Service layer.

## 2. Service (app/Repositories/)
- **Role:** Contains all core business logic.
- **Responsibilities:** Data orchestration, complex validation, processing, and transaction management.
- **Constraint:** Must only delegate data access to the Repository layer.

## 3. Repository (app/Repositories/)
- **Role:** Abstract the data access layer.
- **Responsibilities:** CRUD operations, database querying, and object mapping to domain entities.
- **Constraint:** Must not contain any business logic.
