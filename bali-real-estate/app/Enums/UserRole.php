<?php

namespace App\Enums;

enum UserRole: string {
    case Admin = 'admin';
    case Agent = 'agent';
    case Viewer = 'viewer';
    case User = 'user'; // 一般ユーザー
}