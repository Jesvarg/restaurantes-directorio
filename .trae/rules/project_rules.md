The project is a restaurant directory web application built in Laravel using Blade and MySQL. The system must include three distinct user roles with different access levels and capabilities:

---

1. **Regular User**
   - Can browse public restaurants.
   - Can view details of restaurants (only if they are approved).
   - Can leave reviews (including rating and comment).
   - Reviews must be paginated or lazy-loaded.

2. **Owner**
   - Has all the permissions of a regular user.
   - Can create and edit their own restaurants.
   - When a restaurant is created or edited, it must be sent for admin review.
   - Admin must approve or reject it. In case of rejection, a reason (message) must be added, and the owner will be able to edit and resubmit it.
   - Can submit a suspension request for their restaurant (for maintenance or temporary closures), but it must be approved by the admin.

3. **Administrator**
   - Has full system control.
   - Can delete or block any user (regular or owner).
   - Can approve or reject new or updated restaurants.
     - Rejection requires a written reason that the owner will see.
   - Can directly suspend or delete any restaurant without approval.
   - Can edit any restaurant if needed.
   - Must review suspension requests from owners and approve/reject them.

---

Additional requirements:

- Each restaurant should have a status managed via a **dedicated Enum** or constant (e.g., pending, approved, rejected, suspended).
- The system should send notifications (via **Laravel Notifications**) to admins or owners when:
  - A restaurant is submitted for review.
  - A restaurant is approved or rejected.
  - A suspension is approved/rejected.
- Use **Policies or Middleware** to enforce role-based access.
- Implement **lazy loading or pagination** for reviews to avoid performance issues.
- Include seeders and factories to support development/testing.
- Structure the project so that it is scalable and modular for future features like table reservations or payments.
- Apply soft deletes where needed (e.g., for restaurants and users).

Please suggest and implement any improvements that increase security, performance, maintainability, or user experience. Design the system like it’s for production from day one.
