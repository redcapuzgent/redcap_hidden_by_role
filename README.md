# Hidden by role
> [!CAUTION]
> This module has been deprecated, as its functionality can be built using REDCap's built-in @IF action tag (introduced in REDCap 11.4.0) and user-role smart variables (introduced in REDCap 11.2.0).

## Purpose
This module allows you to hide fields unless the user has a specific role.

## How to use the module
### Using the module
- Enable the module for your project.
- Create a field with this annotation:  
`@HIDE_BY_ROLE=<role that exceptionally can see the field>`
- The role that you enter must be the exact name of the role as defined in the user rights environment.  
Roles with @ symbols are not allowed as they may conflict with other tags.
- If the role cannot be found an error will be thrown.

### Example
```
@HIDE_BY_ROLE=role2
```
