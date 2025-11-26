@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Apps</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item active">Apps</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- PWA App Grid -->
                    <div class="pwa-dashboard">
                        <!-- Search Section -->
                        <div class="pwa-section">
                            <h3 class="section-title">
                                <i class="fas fa-search"></i>
                                Search & Productivity
                            </h3>
                            <div class="apps-grid">
                                <div class="pwa-app-card" data-app="google-search">
                                    <div class="app-icon-container">
                                        <div class="app-icon search">
                                            <i class="fas fa-search"></i>
                                        </div>
                                        <div class="app-badge">Integrated</div>
                                    </div>
                                    <div class="app-info">
                                        <h4 class="app-name">Google Search</h4>
                                        <p class="app-description">Search the web</p>
                                    </div>
                                    <div class="app-actions">
                                        <button class="btn-app-action" data-action="open">
                                            <i class="fas fa-external-link-alt"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="pwa-app-card" data-app="gmail">
                                    <div class="app-icon-container">
                                        <div class="app-icon gmail">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div class="app-badge">PWA</div>
                                    </div>
                                    <div class="app-info">
                                        <h4 class="app-name">Gmail</h4>
                                        <p class="app-description">Email & Communication</p>
                                        <!-- <div class="app-stats">
                                            <span class="stat">12 unread</span>
                                        </div> -->
                                    </div>
                                    <div class="app-actions">
                                        <button class="btn-app-action" data-action="quick">
                                            <i class="fas fa-bolt"></i>
                                        </button>
                                        <button class="btn-app-action" data-action="open">
                                            <i class="fas fa-external-link-alt"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="pwa-app-card" data-app="drive">
                                    <div class="app-icon-container">
                                        <div class="app-icon drive">
                                            <i class="fas fa-cloud"></i>
                                        </div>
                                        <div class="app-badge">PWA</div>
                                    </div>
                                    <div class="app-info">
                                        <h4 class="app-name">Google Drive</h4>
                                        <p class="app-description">Cloud Storage</p>
                                        <!-- <div class="app-stats">
                                            <span class="stat">85% used</span>
                                        </div> -->
                                    </div>
                                    <div class="app-actions">
                                        <button class="btn-app-action" data-action="quick">
                                            <i class="fas fa-bolt"></i>
                                        </button>
                                        <button class="btn-app-action" data-action="open">
                                            <i class="fas fa-external-link-alt"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="pwa-app-card" data-app="calendar">
                                    <div class="app-icon-container">
                                        <div class="app-icon calendar">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        <div class="app-badge">PWA</div>
                                    </div>
                                    <div class="app-info">
                                        <h4 class="app-name">Calendar</h4>
                                        <p class="app-description">Schedule & Events</p>
                                        <!-- <div class="app-stats">
                                            <span class="stat">3 events today</span>
                                        </div> -->
                                    </div>
                                    <div class="app-actions">
                                        <button class="btn-app-action" data-action="quick">
                                            <i class="fas fa-bolt"></i>
                                        </button>
                                        <button class="btn-app-action" data-action="open">
                                            <i class="fas fa-external-link-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- AI & Development Section -->
                        <div class="pwa-section">
                            <h3 class="section-title">
                                <i class="fas fa-robot"></i>
                                AI & Development
                            </h3>
                            <div class="apps-grid">
                                <div class="pwa-app-card" data-app="chatgpt">
                                    <div class="app-icon-container">
                                        <div class="app-icon chatgpt">
                                            <i class="fas fa-robot"></i>
                                        </div>
                                        <div class="app-badge">External</div>
                                    </div>
                                    <div class="app-info">
                                        <h4 class="app-name">ChatGPT</h4>
                                        <p class="app-description">AI Assistant</p>
                                    </div>
                                    <div class="app-actions">
                                        <button class="btn-app-action" data-action="open">
                                            <i class="fas fa-external-link-alt"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="pwa-app-card d-none" data-app="github">
                                    <div class="app-icon-container">
                                        <div class="app-icon github">
                                            <i class="fab fa-github"></i>
                                        </div>
                                        <div class="app-badge">PWA</div>
                                    </div>
                                    <div class="app-info">
                                        <h4 class="app-name">GitHub</h4>
                                        <p class="app-description">Code Repository</p>
                                        <!-- <div class="app-stats">
                                            <span class="stat">5 notifications</span>
                                        </div> -->
                                    </div>
                                    <div class="app-actions">
                                        <button class="btn-app-action" data-action="quick">
                                            <i class="fas fa-bolt"></i>
                                        </button>
                                        <button class="btn-app-action" data-action="open">
                                            <i class="fas fa-external-link-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Communication Section -->
                        <div class="pwa-section">
                            <h3 class="section-title">
                                <i class="fas fa-comments"></i>
                                Communication
                            </h3>
                            <div class="apps-grid">
                                <div class="pwa-app-card" data-app="linkedin">
                                    <div class="app-icon-container">
                                        <div class="app-icon linkedin">
                                            <i class="fab fa-linkedin-in"></i>
                                        </div>
                                        <div class="app-badge">External</div>
                                    </div>
                                    <div class="app-info">
                                        <h4 class="app-name">LinkedIn</h4>
                                        <p class="app-description">Professional Network</p>
                                        <!-- <div class="app-stats">
                                            <span class="stat">8 messages</span>
                                        </div> -->
                                    </div>
                                    <div class="app-actions">
                                        <button class="btn-app-action" data-action="open">
                                            <i class="fas fa-external-link-alt"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="pwa-app-card" data-app="slack">
                                    <div class="app-icon-container">
                                        <div class="app-icon slack">
                                            <i class="fab fa-slack"></i>
                                        </div>
                                        <div class="app-badge">PWA</div>
                                    </div>
                                    <div class="app-info">
                                        <h4 class="app-name">Slack</h4>
                                        <p class="app-description">Team Collaboration</p>
                                        <!-- <div class="app-stats">
                                            <span class="stat">12 unread</span>
                                        </div> -->
                                    </div>
                                    <div class="app-actions">
                                        <button class="btn-app-action" data-action="quick">
                                            <i class="fas fa-bolt"></i>
                                        </button>
                                        <button class="btn-app-action" data-action="open">
                                            <i class="fas fa-external-link-alt"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="pwa-app-card" data-app="whatsapp">
                                    <div class="app-icon-container">
                                        <div class="app-icon whatsapp">
                                            <i class="fab fa-whatsapp"></i>
                                        </div>
                                        <div class="app-badge">PWA</div>
                                    </div>
                                    <div class="app-info">
                                        <h4 class="app-name">WhatsApp</h4>
                                        <p class="app-description">Messaging</p>
                                        <!-- <div class="app-stats">
                                            <span class="stat">3 new</span>
                                        </div> -->
                                    </div>
                                    <div class="app-actions">
                                        <button class="btn-app-action" data-action="quick">
                                            <i class="fas fa-bolt"></i>
                                        </button>
                                        <button class="btn-app-action" data-action="open">
                                            <i class="fas fa-external-link-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Content Section -->
                        <div class="pwa-section">
                            <h3 class="section-title">
                                <i class="fas fa-play-circle"></i>
                                Media & Content
                            </h3>
                            <div class="apps-grid">
                                <div class="pwa-app-card" data-app="youtube">
                                    <div class="app-icon-container">
                                        <div class="app-icon youtube">
                                            <i class="fab fa-youtube"></i>
                                        </div>
                                        <div class="app-badge">PWA</div>
                                    </div>
                                    <div class="app-info">
                                        <h4 class="app-name">YouTube</h4>
                                        <p class="app-description">Video Platform</p>
                                    </div>
                                    <div class="app-actions">
                                        <button class="btn-app-action" data-action="open">
                                            <i class="fas fa-external-link-alt"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="pwa-app-card" data-app="spotify">
                                    <div class="app-icon-container">
                                        <div class="app-icon spotify">
                                            <i class="fab fa-spotify"></i>
                                        </div>
                                        <div class="app-badge">PWA</div>
                                    </div>
                                    <div class="app-info">
                                        <h4 class="app-name">Spotify</h4>
                                        <p class="app-description">Music Streaming</p>
                                    </div>
                                    <div class="app-actions">
                                        <button class="btn-app-action" data-action="open">
                                            <i class="fas fa-external-link-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions Modal -->
                    <div class="modal fade" id="quickActionsModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="quickActionsTitle">Quick Actions</h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="quickActionsContent">
                                    <!-- Dynamic content will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Google Search Modal -->
                    <div class="modal fade" id="searchModal" tabindex="-1">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fas fa-search mr-2"></i>
                                        Google Search
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body p-0">
                                    <iframe 
                                        src="https://www.google.com/search?igu=1" 
                                        class="search-iframe"
                                        title="Google Search">
                                    </iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
    </section>
    <!-- /.content -->
</div>

<style>
    /* PWA Dashboard Styles */
    .pwa-dashboard {
        padding: 20px 0;
    }

    .pwa-section {
        margin-bottom: 40px;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.5rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f8f9fa;
    }

    .section-title i {
        color: #4361ee;
        font-size: 1.3rem;
    }

    .apps-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
        margin-bottom: 10px;
    }

    /* PWA App Card */
    .pwa-app-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 15px;
        position: relative;
        cursor: pointer;
    }

    .pwa-app-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        border-color: #4361ee;
    }

    .app-icon-container {
        position: relative;
        flex-shrink: 0;
    }

    .app-icon {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        transition: transform 0.3s ease;
    }

    .pwa-app-card:hover .app-icon {
        transform: scale(1.1);
    }

    .app-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #4361ee;
        color: white;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .app-info {
        flex: 1;
        min-width: 0;
    }

    .app-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 4px;
    }

    .app-description {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 8px;
    }

    .app-stats {
        display: flex;
        gap: 15px;
    }

    .stat {
        font-size: 0.8rem;
        color: #4361ee;
        background: #f0f4ff;
        padding: 2px 8px;
        border-radius: 10px;
        font-weight: 500;
    }

    .app-actions {
        display: flex;
        gap: 8px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .pwa-app-card:hover .app-actions {
        opacity: 1;
    }

    .btn-app-action {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 10px;
        background: #f8f9fa;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-app-action:hover {
        background: #4361ee;
        color: white;
        transform: scale(1.1);
    }

    /* App-specific colors */
    .search { background: linear-gradient(135deg, #4285f4, #34a853); }
    .linkedin { background: linear-gradient(135deg, #0077b5, #00a0dc); }
    .chatgpt { background: linear-gradient(135deg, #10a37f, #1a7f64); }
    .gmail { background: linear-gradient(135deg, #ea4335, #d93e30); }
    .drive { background: linear-gradient(135deg, #4285f4, #3367d6); }
    .calendar { background: linear-gradient(135deg, #34a853, #2d924a); }
    .github { background: linear-gradient(135deg, #333, #24292e); }
    .slack { background: linear-gradient(135deg, #4a154b, #3a0f3a); }
    .whatsapp { background: linear-gradient(135deg, #25d366, #20bd5a); }
    .youtube { background: linear-gradient(135deg, #ff0000, #cc0000); }
    .spotify { background: linear-gradient(135deg, #1db954, #1aa34e); }

    /* Modal Styles */
    .search-iframe {
        width: 100%;
        height: 600px;
        border: none;
        border-radius: 0 0 8px 8px;
    }

    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        padding: 20px 0;
    }

    .quick-action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px 15px;
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        text-decoration: none;
        color: #495057;
        transition: all 0.3s ease;
        text-align: center;
    }

    .quick-action-btn:hover {
        background: #4361ee;
        color: white;
        border-color: #4361ee;
        transform: translateY(-2px);
        text-decoration: none;
    }

    .quick-action-btn i {
        font-size: 24px;
        margin-bottom: 8px;
    }

    .quick-action-btn span {
        font-size: 12px;
        font-weight: 500;
    }

    /* PWA Installation Prompt */
    .pwa-install-prompt {
        background: linear-gradient(135deg, #4361ee, #3a56d4);
        color: white;
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }

    .pwa-install-content {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .pwa-install-icon {
        font-size: 24px;
    }

    .pwa-install-text h4 {
        margin: 0;
        font-size: 1.1rem;
    }

    .pwa-install-text p {
        margin: 0;
        opacity: 0.9;
        font-size: 0.9rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .apps-grid {
            grid-template-columns: 1fr;
        }

        .pwa-app-card {
            padding: 15px;
        }

        .app-icon {
            width: 50px;
            height: 50px;
            font-size: 20px;
        }

        .app-actions {
            opacity: 1; /* Always show on mobile */
        }

        .pwa-install-prompt {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .section-title {
            font-size: 1.3rem;
        }

        .app-name {
            font-size: 1rem;
        }

        .app-description {
            font-size: 0.85rem;
        }
    }

    /* Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .pwa-app-card {
        animation: fadeInUp 0.5s ease forwards;
    }

    /* Stagger animation for cards */
    .pwa-app-card:nth-child(1) { animation-delay: 0.1s; }
    .pwa-app-card:nth-child(2) { animation-delay: 0.2s; }
    .pwa-app-card:nth-child(3) { animation-delay: 0.3s; }
    .pwa-app-card:nth-child(4) { animation-delay: 0.4s; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // App configurations
        const appConfigs = {
            'google-search': {
                url: 'https://google.com',
                
            },
            'gmail': {
                url: 'https://mail.google.com',
                quickActions: [
                    { name: 'Inbox', url: 'https://mail.google.com/mail/u/0/#inbox', icon: 'fas fa-inbox' },
                    { name: 'Compose', url: 'https://mail.google.com/mail/u/0/#compose', icon: 'fas fa-edit' },
                    { name: 'Starred', url: 'https://mail.google.com/mail/u/0/#starred', icon: 'fas fa-star' },
                    { name: 'Sent', url: 'https://mail.google.com/mail/u/0/#sent', icon: 'fas fa-paper-plane' }
                ]
            },
            'drive': {
                url: 'https://drive.google.com',
                quickActions: [
                    { name: 'My Drive', url: 'https://drive.google.com', icon: 'fas fa-cloud' },
                    { name: 'Google Docs', url: 'https://docs.google.com', icon: 'fas fa-file-alt' },
                    { name: 'Google Sheets', url: 'https://sheets.google.com', icon: 'fas fa-table' },
                    { name: 'Google Slides', url: 'https://slides.google.com', icon: 'fas fa-presentation' }
                ]
            },
            'calendar': {
                url: 'https://calendar.google.com',
                quickActions: [
                    { name: 'Month View', url: 'https://calendar.google.com/calendar/u/0/r/month', icon: 'fas fa-calendar' },
                    { name: 'Week View', url: 'https://calendar.google.com/calendar/u/0/r/week', icon: 'fas fa-calendar-week' },
                    { name: 'Day View', url: 'https://calendar.google.com/calendar/u/0/r/day', icon: 'fas fa-calendar-day' },
                    { name: 'Create Event', url: 'https://calendar.google.com/calendar/u/0/r/eventedit', icon: 'fas fa-plus' }
                ]
            },
            'chatgpt': {
                url: 'https://chat.openai.com',
                quickActions: []
            },
            'github': {
                url: 'https://github.com',
                quickActions: [
                    { name: 'Repositories', url: 'https://github.com', icon: 'fas fa-code-branch' },
                    { name: 'Pull Requests', url: 'https://github.com/pulls', icon: 'fas fa-code-merge' },
                    { name: 'Issues', url: 'https://github.com/issues', icon: 'fas fa-exclamation-circle' },
                    { name: 'Notifications', url: 'https://github.com/notifications', icon: 'fas fa-bell' }
                ]
            },
            'linkedin': {
                url: 'https://www.linkedin.com',
                quickActions: [
                    { name: 'Feed', url: 'https://www.linkedin.com/feed/', icon: 'fas fa-newspaper' },
                    { name: 'My Network', url: 'https://www.linkedin.com/mynetwork/', icon: 'fas fa-users' },
                    { name: 'Jobs', url: 'https://www.linkedin.com/jobs/', icon: 'fas fa-briefcase' },
                    { name: 'Messaging', url: 'https://www.linkedin.com/messaging/', icon: 'fas fa-comments' }
                ]
            },
            'slack': {
                url: 'https://slack.com',
                quickActions: [
                    { name: 'Channels', url: 'https://slack.com', icon: 'fas fa-hashtag' },
                    { name: 'Direct Messages', url: 'https://slack.com', icon: 'fas fa-user' },
                    { name: 'Activity', url: 'https://slack.com', icon: 'fas fa-bell' },
                    { name: 'Files', url: 'https://slack.com', icon: 'fas fa-file' }
                ]
            },
            'whatsapp': {
                url: 'https://web.whatsapp.com',
                quickActions: [
                    { name: 'Chats', url: 'https://web.whatsapp.com', icon: 'fas fa-comment' },
                    { name: 'Status', url: 'https://web.whatsapp.com', icon: 'fas fa-circle' },
                    { name: 'Calls', url: 'https://web.whatsapp.com', icon: 'fas fa-phone' }
                ]
            },
            'youtube': {
                url: 'https://www.youtube.com',
                quickActions: [
                    { name: 'Home', url: 'https://www.youtube.com', icon: 'fas fa-home' },
                    { name: 'Trending', url: 'https://www.youtube.com/feed/trending', icon: 'fas fa-fire' },
                    { name: 'Subscriptions', url: 'https://www.youtube.com/feed/subscriptions', icon: 'fas fa-star' },
                    { name: 'Library', url: 'https://www.youtube.com/feed/library', icon: 'fas fa-history' }
                ]
            },
            'spotify': {
                url: 'https://open.spotify.com',
                quickActions: [
                    { name: 'Home', url: 'https://open.spotify.com', icon: 'fas fa-home' },
                    { name: 'Search', url: 'https://open.spotify.com/search', icon: 'fas fa-search' },
                    { name: 'Your Library', url: 'https://open.spotify.com/collection', icon: 'fas fa-book' }
                ]
            }
        };

        // Handle app card clicks
        document.querySelectorAll('.pwa-app-card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (!e.target.closest('.btn-app-action')) {
                    const appName = this.getAttribute('data-app');
                    openApp(appName);
                }
            });
        });

        // Handle action buttons
        document.querySelectorAll('.btn-app-action').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const action = this.getAttribute('data-action');
                const appCard = this.closest('.pwa-app-card');
                const appName = appCard.getAttribute('data-app');
                
                if (action === 'open') {
                    openApp(appName);
                } else if (action === 'quick') {
                    showQuickActions(appName);
                }
            });
        });

        // Open app function
        function openApp(appName) {
            const config = appConfigs[appName];
            
            if (appName === 'google-search') {
                $('#searchModal').modal('show');
            } else {
                // Open in new window with PWA-like dimensions
                const width = 1200;
                const height = 800;
                const left = (screen.width - width) / 2;
                const top = (screen.height - height) / 2;
                
                window.open(config.url, `app-${appName}`, 
                    `width=${width},height=${height},left=${left},top=${top},resizable=yes,scrollbars=yes`);
            }
        }

        // Show quick actions modal
        function showQuickActions(appName) {
            const config = appConfigs[appName];
            const modal = $('#quickActionsModal');
            const title = document.getElementById('quickActionsTitle');
            const content = document.getElementById('quickActionsContent');
            
            title.textContent = `${getAppName(appName)} Quick Actions`;
            
            if (config.quickActions.length > 0) {
                let actionsHTML = '<div class="quick-actions-grid">';
                config.quickActions.forEach(action => {
                    actionsHTML += `
                        <a href="${action.url}" class="quick-action-btn" target="_blank">
                            <i class="${action.icon}"></i>
                            <span>${action.name}</span>
                        </a>
                    `;
                });
                actionsHTML += '</div>';
                content.innerHTML = actionsHTML;
            } else {
                content.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-bolt fa-3x text-muted mb-3"></i>
                        <p>No quick actions available for this app.</p>
                        <button class="btn btn-primary" onclick="openApp('${appName}')">
                            Open ${getAppName(appName)}
                        </button>
                    </div>
                `;
            }
            
            modal.modal('show');
        }

        // Helper function to get app display name
        function getAppName(appKey) {
            const card = document.querySelector(`[data-app="${appKey}"]`);
            return card ? card.querySelector('.app-name').textContent : appKey;
        }

        // PWA Installation Prompt (simulated)
        function showPWAInstallPrompt() {
            // In a real PWA, you would use the BeforeInstallPrompt event
            console.log('PWA installation available');
        }

        // Initialize
        showPWAInstallPrompt();
    });

    // Make functions globally available for modal buttons
    function openApp(appName) {
        const event = new CustomEvent('openApp', { detail: { appName } });
        document.dispatchEvent(event);
    }
</script>

<!-- Add this to your layout for PWA functionality -->
<script>
    // PWA Service Worker Registration (example)
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js').then(function(registration) {
            console.log('SW registered: ', registration);
        }).catch(function(registrationError) {
            console.log('SW registration failed: ', registrationError);
        });
    }

    // Listen for openApp events (for modal buttons)
    document.addEventListener('openApp', function(e) {
        const appName = e.detail.appName;
        // Implementation would go here
    });
</script>
@endsection