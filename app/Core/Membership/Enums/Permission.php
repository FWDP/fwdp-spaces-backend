<?php

namespace App\Core\Membership\Enums;

enum Permission: string
{
    case MANAGE_USERS = 'manage_users';
    case MANAGE_ROLES = 'manage_roles';
    case VIEW_PERMISSIONS = 'view_permissions';

    case CREATE_COURSES = 'create_courses';
    case EDIT_COURSES = 'edit_courses';
    case DELETE_COURSES = 'delete_courses';
    case PUBLISH_COURSES = 'publish_courses';

    case ENROLL_COURSES = 'enroll_courses';
}
