# Deleting Users from the Vivedia system

## Stop a user account from logging in

To stop a user account from logging in to the admin area, set the `deleted_at` field in the `users` table to the date.

## Remove a user from the system completely

**STOP!** it is very unlikely you will need to remove a user from the system completely.

Are you sure you understand what you are doing?

The four tables that need to be deleted from are
 - `item_order_notes` (if there are records in here for the user make sure that they can be deleted, if not reasign the user_id)
 - `role_user`
 - `site_user`
 - `user`

### Reference SQL

    select RU.id, RU.role_id, U.firstname, U.lastname, U.email, U.id as user_id from role_user RU INNER JOIN users U ON RU.user_id = U.id;
    select SU.id, SU.site_id, U.firstname, U.lastname, U.email, U.id as user_id from site_user SU INNER JOIN users U ON SU.user_id = U.id;
    SELECT user_id FROM memorialisation.item_order_notes;
    SELECT user_id FROM memorialisation.role_user;
    SELECT user_id FROM memorialisation.site_user;
    SELECT id FROM memorialisation.users;