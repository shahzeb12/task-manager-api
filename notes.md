HTTP Methods:

- GET - To retreive the records
- POST - to create new record
- PUT - to edit existing columns
- PATCH - to edit existing column (single value)
- DELETE - to remove the record.


2xx - success
4xx - client error
5xx - server error


API Versioning:

 - Use it so your current api wont be impacted.
 - Eg:

 	- /api/v1/tasks  - from web
 	- /api/v2/tasks  - mobile

 - Protecting from breaking changes.
 - Gracefull scalling your API grows.
 - Clear communications to the users.


 Elequent API Resources:

 - Use for builind API
 - It act as a transformation layer between elequent model and json responses returned to client.



 Controller isolation:

 	- Entity Controller should be used for main CRUD options (Create, Read, Update, Delete)
 	- Create seperate controller if you have more action, you may use __ invoke method to create single action controller. (TaskCompleteController)
 	- use PATCH http method to update only single column.


Factory Pattern:

    - use factory for testing Task::factory()->create()


**Laravel Sanctum API Token:**

    - Supports Token and Session Based Authentication.
    - Easy to setup 
    - best for SPA
    - Doesn't Require OAuth
    - Official Laravel Package



    


