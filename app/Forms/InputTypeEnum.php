<?php

namespace App\Forms;

enum InputTypeEnum : string {
    case TEXT = 'text';
	case EMAIL = 'email';
	case NUMBER = 'number';
	case PASSWORD = 'password';
	case DATE = 'date';
	case TIME = 'time';
	case TEXTAREA = 'textarea';
	case SELECT = 'select';
	case CHECKBOX = 'checkbox';
	case FILE = 'file';
	case HIDDEN = 'hidden';
}