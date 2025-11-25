<?php
$role = $_SESSION['role'];
?>
<div class="topnav">
    <div class="nav-container">
        <div class="left">
            <div class="logo">
                <span class="logo-icon">🏢</span>
                <span class="logo-text">HRM System</span>
            </div>

            <nav class="nav-menu">
                <a href="index.php?page=home" class="menu-item <?= $page=='home'?'active':'' ?>">
                    <span class="menu-icon">🏠</span>
                    <span class="menu-text">홈</span>
                </a>

                <?php if($role=='ADMIN'): ?>
                    <a href="index.php?page=employees_list" class="menu-item <?= $page=='employees_list'?'active':'' ?>">
                        <span class="menu-icon">👥</span>
                        <span class="menu-text">직원 관리</span>
                    </a>
                <?php endif; ?>

                <a href="index.php?page=attendance" class="menu-item <?= $page=='attendance'?'active':'' ?>">
                    <span class="menu-icon">📅</span>
                    <span class="menu-text">근태 관리</span>
                </a>

                <a href="index.php?page=my_profile" class="menu-item <?= $page=='my_profile'?'active':'' ?>">
                    <span class="menu-icon">👤</span>
                    <span class="menu-text">내 정보</span>
                </a>
            </nav>
        </div>

        <div class="right">
            <div class="user-profile">
                <div class="user-avatar">
                    <?= mb_substr($_SESSION['name'], 0, 1) ?>
                </div>
                <div class="user-details">
                    <span class="user-name"><?= $_SESSION['name'] ?></span>
                    <span class="user-role"><?= $role == 'ADMIN' ? '관리자' : '직원' ?></span>
                </div>
            </div>
            <a href="auth/logout.php" class="logout-btn">
                <span class="logout-icon">🚪</span>
                로그아웃
            </a>
        </div>
    </div>
</div>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .topnav {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 80px;
        background: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        z-index: 1000;
        border-bottom: 1px solid #f1f5f9;
    }

    .nav-container {
        max-width: 1600px;
        height: 100%;
        margin: 0 auto;
        padding: 0 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* 왼쪽 영역 */
    .left {
        display: flex;
        align-items: center;
        gap: 40px;
        flex: 1;
    }

    /* 로고 */
    .logo {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-right: 40px;
        border-right: 2px solid #f1f5f9;
    }

    .logo-icon {
        font-size: 32px;
        filter: drop-shadow(0 2px 4px rgba(102, 126, 234, 0.3));
    }

    .logo-text {
        font-size: 22px;
        font-weight: 800;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.5px;
    }

    /* 네비게이션 메뉴 */
    .nav-menu {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .menu-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        text-decoration: none;
        color: #64748b;
        font-size: 15px;
        font-weight: 600;
        border-radius: 12px;
        transition: all 0.2s;
        position: relative;
    }

    .menu-icon {
        font-size: 18px;
        transition: transform 0.2s;
    }

    .menu-item:hover {
        background: #f8fafc;
        color: #334155;
    }

    .menu-item:hover .menu-icon {
        transform: scale(1.1);
    }

    .menu-item.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .menu-item.active::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 6px;
        height: 6px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
    }

    /* 오른쪽 영역 */
    .right {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    /* 사용자 프로필 */
    .user-profile {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 16px;
        background: #f8fafc;
        border-radius: 12px;
        transition: all 0.2s;
    }

    .user-profile:hover {
        background: #f1f5f9;
    }

    .user-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .user-details {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .user-name {
        font-size: 15px;
        font-weight: 600;
        color: #0f172a;
        line-height: 1.2;
    }

    .user-role {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
    }

    /* 로그아웃 버튼 */
    .logout-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        text-decoration: none;
        color: #dc2626;
        font-size: 14px;
        font-weight: 600;
        border-radius: 12px;
        background: #fef2f2;
        transition: all 0.2s;
        border: 2px solid transparent;
    }

    .logout-icon {
        font-size: 16px;
        transition: transform 0.2s;
    }

    .logout-btn:hover {
        background: #fee2e2;
        border-color: #fecaca;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
    }

    .logout-btn:hover .logout-icon {
        transform: translateX(2px);
    }

    .logout-btn:active {
        transform: translateY(0);
    }

    /* 콘텐츠 영역 */
    .content {
        margin-top: 80px;
        min-height: calc(100vh - 80px);
        background: #f5f7fa;
    }

    /* 반응형 */
    @media (max-width: 1024px) {
        .nav-container {
            padding: 0 20px;
        }

        .left {
            gap: 20px;
        }

        .logo {
            padding-right: 20px;
        }

        .logo-text {
            display: none;
        }

        .menu-text {
            display: none;
        }

        .menu-item {
            padding: 12px;
        }

        .menu-icon {
            font-size: 20px;
        }

        .user-details {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .topnav {
            height: 70px;
        }

        .content {
            margin-top: 70px;
        }

        .nav-menu {
            gap: 4px;
        }

        .menu-item {
            padding: 10px;
        }

        .logo-icon {
            font-size: 24px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            font-size: 16px;
        }

        .logout-btn {
            padding: 10px 16px;
            font-size: 13px;
        }

        .right {
            gap: 12px;
        }
    }

    /* 애니메이션 */
    @keyframes slideDown {
        from {
            transform: translateY(-100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .topnav {
        animation: slideDown 0.3s ease-out;
    }
</style>