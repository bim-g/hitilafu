<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($this->errorData['type']); ?> - <?php echo htmlspecialchars($this->appName); ?>
    </title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
        --bg-primary: #0d1117;
        --bg-secondary: #161b22;
        --bg-tertiary: #1c2128;
        --bg-hover: #21262d;
        --text-primary: #e6edf3;
        --text-secondary: #8b949e;
        --text-muted: #6e7681;
        --border-color: #30363d;
        --accent-primary: #58a6ff;
        --accent-secondary: #79c0ff;
        --error-red: #f85149;
        --warning-yellow: #d29922;
        --success-green: #3fb950;
        --code-bg: #0d1117;
        --highlight-line: rgba(255, 184, 76, 0.15);
        --shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
    }

    body.light-theme {
        --bg-primary: #ffffff;
        --bg-secondary: #f6f8fa;
        --bg-tertiary: #eaeef2;
        --bg-hover: #dfe3e8;
        --text-primary: #24292f;
        --text-secondary: #57606a;
        --text-muted: #6e7781;
        --border-color: #d0d7de;
        --accent-primary: #0969da;
        --accent-secondary: #0550ae;
        --error-red: #cf222e;
        --warning-yellow: #9a6700;
        --success-green: #1a7f37;
        --code-bg: #f6f8fa;
        --highlight-line: rgba(255, 184, 76, 0.25);
        --shadow: 0 8px 24px rgba(140, 149, 159, 0.2);
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Noto Sans', Helvetica, Arial, sans-serif;
        background: var(--bg-primary);
        color: var(--text-primary);
        line-height: 1.6;
        overflow-x: hidden;
    }

    .error-container {
        min-height: 100vh;
        padding: 20px;
    }

    .error-header {
        background: linear-gradient(135deg, var(--error-red) 0%, #ff6b6b 100%);
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 20px;
        box-shadow: var(--shadow);
        position: relative;
        overflow: hidden;
    }

    .error-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M 20 0 L 0 0 0 20" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        opacity: 0.3;
    }

    .error-header-content {
        position: relative;
        z-index: 1;
    }

    .error-type {
        font-size: 14px;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.9);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 10px;
    }

    .error-message {
        font-size: 24px;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 15px;
        line-height: 1.4;
    }

    .error-location {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        color: rgba(255, 255, 255, 0.85);
        background: rgba(0, 0, 0, 0.2);
        padding: 10px 15px;
        border-radius: 6px;
        font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
    }

    .error-location svg {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    .theme-toggle {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 10px 15px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text-primary);
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: var(--shadow);
    }

    .theme-toggle:hover {
        background: var(--bg-hover);
        transform: translateY(-2px);
    }

    .tabs-container {
        background: var(--bg-secondary);
        border-radius: 12px;
        box-shadow: var(--shadow);
        overflow: hidden;
        border: 1px solid var(--border-color);
    }

    .tabs-header {
        display: flex;
        gap: 4px;
        background: var(--bg-tertiary);
        padding: 8px;
        border-bottom: 1px solid var(--border-color);
        overflow-x: auto;
    }

    .tab-button {
        background: transparent;
        border: none;
        padding: 10px 20px;
        color: var(--text-secondary);
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        border-radius: 6px;
        transition: all 0.3s ease;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .tab-button:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }

    .tab-button.active {
        background: var(--bg-secondary);
        color: var(--accent-primary);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .tab-content {
        display: none;
        padding: 20px;
        animation: fadeIn 0.3s ease;
    }

    .tab-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .stack-trace-item {
        background: var(--bg-tertiary);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        margin-bottom: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .stack-trace-item:hover {
        border-color: var(--accent-primary);
        box-shadow: 0 4px 12px rgba(88, 166, 255, 0.1);
    }

    .stack-trace-header {
        padding: 15px 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 15px;
        background: var(--bg-tertiary);
        transition: background 0.3s ease;
    }

    .stack-trace-header:hover {
        background: var(--bg-hover);
    }

    .stack-trace-index {
        background: var(--accent-primary);
        color: var(--bg-primary);
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .stack-trace-info {
        flex: 1;
        min-width: 0;
    }

    .stack-trace-function {
        font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
        font-size: 14px;
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 4px;
    }

    .stack-trace-location {
        font-size: 13px;
        color: var(--text-secondary);
        font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .stack-trace-expand {
        color: var(--text-secondary);
        transition: transform 0.3s ease;
    }

    .stack-trace-item.expanded .stack-trace-expand {
        transform: rotate(180deg);
    }

    .stack-trace-code {
        display: none;
        background: var(--code-bg);
        border-top: 1px solid var(--border-color);
        padding: 15px;
        max-height: 400px;
        overflow: auto;
    }

    .stack-trace-item.expanded .stack-trace-code {
        display: block;
    }

    .code-line {
        display: flex;
        padding: 2px 0;
        font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
        font-size: 13px;
        line-height: 1.5;
    }

    .code-line-number {
        color: var(--text-muted);
        text-align: right;
        padding-right: 20px;
        min-width: 50px;
        user-select: none;
        flex-shrink: 0;
    }

    .code-line-content {
        flex: 1;
        color: var(--text-primary);
        white-space: pre;
        overflow-x: auto;
    }

    .code-line.highlight {
        background: var(--highlight-line);
        border-left: 3px solid var(--error-red);
    }

    .code-line.highlight .code-line-number {
        color: var(--error-red);
        font-weight: 700;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .data-table th {
        text-align: left;
        padding: 12px;
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        font-weight: 600;
        border-bottom: 1px solid var(--border-color);
    }

    .data-table td {
        padding: 12px;
        border-bottom: 1px solid var(--border-color);
        font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
        font-size: 13px;
    }

    .data-table tr:hover {
        background: var(--bg-hover);
    }

    .data-key {
        color: var(--accent-primary);
        font-weight: 600;
    }

    .data-value {
        color: var(--text-primary);
        word-break: break-all;
    }

    .solution-card {
        background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .solution-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, var(--success-green) 0%, var(--accent-primary) 100%);
    }

    .solution-card:hover {
        transform: translateX(5px);
        box-shadow: var(--shadow);
    }

    .solution-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .solution-title svg {
        color: var(--success-green);
        flex-shrink: 0;
    }

    .solution-description {
        color: var(--text-secondary);
        line-height: 1.7;
        margin-bottom: 15px;
    }

    .solution-links {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .solution-link {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        background: var(--accent-primary);
        color: var(--bg-primary);
        text-decoration: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .solution-link:hover {
        background: var(--accent-secondary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(88, 166, 255, 0.3);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-secondary);
    }

    .empty-state svg {
        width: 64px;
        height: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-state-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--text-primary);
    }

    .search-box {
        margin-bottom: 20px;
    }

    .search-input {
        width: 100%;
        padding: 12px 16px;
        background: var(--bg-tertiary);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        color: var(--text-primary);
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--accent-primary);
        box-shadow: 0 0 0 3px rgba(88, 166, 255, 0.1);
    }

    .badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        background: var(--bg-tertiary);
        color: var(--text-secondary);
    }

    .badge-primary {
        background: rgba(88, 166, 255, 0.2);
        color: var(--accent-primary);
    }

    .badge-success {
        background: rgba(63, 185, 80, 0.2);
        color: var(--success-green);
    }

    .badge-error {
        background: rgba(248, 81, 73, 0.2);
        color: var(--error-red);
    }

    .env-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
    }

    .env-card {
        background: var(--bg-tertiary);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 15px;
        transition: all 0.3s ease;
    }

    .env-card:hover {
        border-color: var(--accent-primary);
        transform: translateY(-2px);
    }

    .env-card-label {
        color: var(--text-secondary);
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .env-card-value {
        color: var(--text-primary);
        font-size: 16px;
        font-weight: 700;
        font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
    }

    .extensions-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 10px;
        margin-top: 15px;
    }

    .extension-item {
        background: var(--bg-tertiary);
        border: 1px solid var(--border-color);
        padding: 10px 15px;
        border-radius: 6px;
        font-size: 13px;
        color: var(--text-primary);
        font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
    }

    @media (max-width: 768px) {
        .error-container {
            padding: 10px;
        }

        .error-header {
            padding: 20px;
        }

        .error-message {
            font-size: 18px;
        }

        .tabs-header {
            overflow-x: auto;
        }

        .tab-content {
            padding: 15px;
        }

        .env-grid {
            grid-template-columns: 1fr;
        }
    }

    .json-viewer {
        background: var(--code-bg);
        padding: 15px;
        border-radius: 8px;
        overflow: auto;
        max-height: 500px;
    }

    .json-key {
        color: var(--accent-primary);
    }

    .json-string {
        color: var(--success-green);
    }

    .json-number {
        color: var(--warning-yellow);
    }

    .json-boolean {
        color: var(--accent-secondary);
    }

    .json-null {
        color: var(--text-muted);
    }
    </style>
</head>

<body class="<?php echo $this->theme; ?>-theme">
    <div class="theme-toggle" onclick="toggleTheme()">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
            <path
                d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z" />
        </svg>
        <span>Theme</span>
    </div>

    <div class="error-container">
        <div class="error-header">
            <div class="error-header-content">
                <div class="error-type">
                    <?php echo htmlspecialchars($this->errorData['type']); ?>
                </div>
                <div class="error-message">
                    <?php echo htmlspecialchars($this->errorData['message']); ?>
                </div>
                <div class="error-location">
                    <svg viewBox="0 0 16 16" fill="currentColor">
                        <path
                            d="M2.5 3.5a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-11zm0 3a.5.5 0 0 1 0-1h6a.5.5 0 0 1 0 1h-6zm0 3a.5.5 0 0 1 0-1h6a.5.5 0 0 1 0 1h-6zm0 3a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-11z" />
                    </svg>
                    <span><?php echo htmlspecialchars($this->errorData['file']); ?>:<?php echo $this->errorData['line']; ?></span>
                </div>
            </div>
        </div>

        <div class="tabs-container">
            <div class="tabs-header">
                <button class="tab-button active" onclick="switchTab('stack-trace')">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                        <path
                            d="M5 3.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0zm0 2.122a2.25 2.25 0 1 0-1.5 0v.878A2.25 2.25 0 0 0 5.75 8.5h1.5v2.128a2.251 2.251 0 1 0 1.5 0V8.5h1.5a2.25 2.25 0 0 0 2.25-2.25v-.878a2.25 2.25 0 1 0-1.5 0v.878a.75.75 0 0 1-.75.75h-4.5A.75.75 0 0 1 5 6.25v-.878zm3.75 7.378a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0zm3-8.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5z" />
                    </svg>
                    Stack Trace
                </button>
                <button class="tab-button" onclick="switchTab('solutions')">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                        <path
                            d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z" />
                    </svg>
                    Solutions
                </button>
                <button class="tab-button" onclick="switchTab('request')">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                        <path
                            d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z" />
                    </svg>
                    Request
                </button>
                <button class="tab-button" onclick="switchTab('environment')">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                        <path
                            d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2l-2.218-.887zm3.564 1.426L5.596 5 8 5.961 14.154 3.5l-2.404-.961zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z" />
                    </svg>
                    Environment
                </button>
            </div>

            <div id="stack-trace" class="tab-content active">
                <div class="search-box">
                    <input type="text" class="search-input" placeholder="Search in stack trace..."
                        onkeyup="searchStackTrace(this.value)">
                </div>
                <?php foreach ($this->errorData['trace'] as $frame): ?>
                <div class="stack-trace-item"
                    data-search-content="<?php echo htmlspecialchars($frame['file'] . ' ' . $frame['function']); ?>">
                    <div class="stack-trace-header" onclick="toggleStackTrace(this)">
                        <div class="stack-trace-index"><?php echo $frame['index']; ?></div>
                        <div class="stack-trace-info">
                            <div class="stack-trace-function">
                                <?php
                                    if ($frame['class']) {
                                        echo htmlspecialchars($frame['class'] . $frame['type'] . $frame['function']);
                                    } else {
                                        echo htmlspecialchars($frame['function']);
                                    }
                                    ?>
                                <?php if ($frame['args']): ?>
                                <span
                                    style="color: var(--text-secondary);">(<?php echo htmlspecialchars($frame['args']); ?>)</span>
                                <?php endif; ?>
                            </div>
                            <div class="stack-trace-location">
                                <?php echo htmlspecialchars($frame['file']); ?>
                                <?php if ($frame['line']): ?>
                                :<?php echo $frame['line']; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="stack-trace-expand">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                            </svg>
                        </div>
                    </div>
                    <?php if (!empty($frame['context'])): ?>
                    <div class="stack-trace-code">
                        <?php foreach ($frame['context'] as $line): ?>
                        <div class="code-line <?php echo $line['highlight'] ? 'highlight' : ''; ?>">
                            <div class="code-line-number"><?php echo $line['number']; ?></div>
                            <div class="code-line-content"><?php echo htmlspecialchars($line['content']); ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>

            <div id="solutions" class="tab-content">
                <?php if (empty($this->errorData['solutions'])): ?>
                <div class="empty-state">
                    <svg viewBox="0 0 16 16" fill="currentColor">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                        <path
                            d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z" />
                    </svg>
                    <div class="empty-state-title">No Solutions Available</div>
                    <div>No automatic solutions could be generated for this error.</div>
                </div>
                <?php else: ?>
                <?php foreach ($this->errorData['solutions'] as $solution): ?>
                <div class="solution-card">
                    <div class="solution-title">
                        <svg width="20" height="20" viewBox="0 0 16 16" fill="currentColor">
                            <path
                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                        </svg>
                        <?php echo htmlspecialchars($solution['title']); ?>
                    </div>
                    <div class="solution-description">
                        <?php echo htmlspecialchars($solution['description']); ?>
                    </div>
                    <?php if (!empty($solution['links'])): ?>
                    <div class="solution-links">
                        <?php foreach ($solution['links'] as $link): ?>
                        <a href="<?php echo htmlspecialchars($link['url']); ?>" class="solution-link" target="_blank"
                            rel="noopener">
                            <?php echo htmlspecialchars($link['text']); ?>
                            <svg width="12" height="12" viewBox="0 0 16 16" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z" />
                                <path fill-rule="evenodd"
                                    d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z" />
                            </svg>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div id="request" class="tab-content">
                <h3 style="margin-bottom: 20px; color: var(--text-primary);">Request Details</h3>

                <div style="margin-bottom: 20px;">
                    <span
                        class="badge badge-primary"><?php echo htmlspecialchars($this->errorData['request']['method']); ?></span>
                    <span style="color: var(--text-secondary); margin-left: 10px; font-family: monospace;">
                        <?php echo htmlspecialchars($this->errorData['request']['url']); ?>
                    </span>
                </div>

                <?php
                $requestSections = [
                    'GET Parameters' => $this->errorData['request']['get'],
                    'POST Data' => $this->errorData['request']['post'],
                    'Cookies' => $this->errorData['request']['cookies'],
                    'Session' => $this->errorData['request']['session'],
                    'Headers' => $this->errorData['request']['headers']
                ];

                foreach ($requestSections as $title => $data):
                    if (empty($data)) continue;
                ?>
                <h4 style="margin: 25px 0 15px; color: var(--text-primary); font-size: 16px;"><?php echo $title; ?></h4>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 30%;">Key</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $key => $value): ?>
                        <tr>
                            <td class="data-key"><?php echo htmlspecialchars($key); ?></td>
                            <td class="data-value">
                                <?php echo htmlspecialchars(is_array($value) ? json_encode($value, JSON_PRETTY_PRINT) : $value); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endforeach; ?>
            </div>

            <div id="environment" class="tab-content">
                <h3 style="margin-bottom: 20px; color: var(--text-primary);">Environment Information</h3>

                <div class="env-grid">
                    <div class="env-card">
                        <div class="env-card-label">PHP Version</div>
                        <div class="env-card-value">
                            <?php echo htmlspecialchars($this->errorData['environment']['php_version']); ?></div>
                    </div>
                    <div class="env-card">
                        <div class="env-card-label">Operating System</div>
                        <div class="env-card-value">
                            <?php echo htmlspecialchars($this->errorData['environment']['os']); ?></div>
                    </div>
                    <div class="env-card">
                        <div class="env-card-label">Server Software</div>
                        <div class="env-card-value">
                            <?php echo htmlspecialchars($this->errorData['environment']['server_software']); ?></div>
                    </div>
                    <div class="env-card">
                        <div class="env-card-label">Memory Limit</div>
                        <div class="env-card-value">
                            <?php echo htmlspecialchars($this->errorData['environment']['memory_limit']); ?></div>
                    </div>
                    <div class="env-card">
                        <div class="env-card-label">Max Execution Time</div>
                        <div class="env-card-value">
                            <?php echo htmlspecialchars($this->errorData['environment']['max_execution_time']); ?>s
                        </div>
                    </div>
                    <div class="env-card">
                        <div class="env-card-label">Timezone</div>
                        <div class="env-card-value">
                            <?php echo htmlspecialchars($this->errorData['environment']['timezone']); ?></div>
                    </div>
                </div>

                <h4 style="margin: 30px 0 15px; color: var(--text-primary);">Loaded Extensions</h4>
                <div class="extensions-list">
                    <?php foreach ($this->errorData['environment']['loaded_extensions'] as $ext): ?>
                    <div class="extension-item"><?php echo htmlspecialchars($ext); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
    function switchTab(tabName) {
        // Hide all tabs
        const tabs = document.querySelectorAll('.tab-content');
        tabs.forEach(tab => tab.classList.remove('active'));

        // Deactivate all buttons
        const buttons = document.querySelectorAll('.tab-button');
        buttons.forEach(button => button.classList.remove('active'));

        // Show selected tab
        document.getElementById(tabName).classList.add('active');

        // Activate clicked button
        event.target.closest('.tab-button').classList.add('active');
    }

    function toggleStackTrace(element) {
        const item = element.closest('.stack-trace-item');
        item.classList.toggle('expanded');
    }

    function toggleTheme() {
        const body = document.body;
        if (body.classList.contains('dark-theme')) {
            body.classList.remove('dark-theme');
            body.classList.add('light-theme');
            localStorage.setItem('theme', 'light');
        } else {
            body.classList.remove('light-theme');
            body.classList.add('dark-theme');
            localStorage.setItem('theme', 'dark');
        }
    }

    function searchStackTrace(query) {
        const items = document.querySelectorAll('.stack-trace-item');
        const searchLower = query.toLowerCase();

        items.forEach(item => {
            const content = item.getAttribute('data-search-content').toLowerCase();
            if (content.includes(searchLower) || searchLower === '') {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Load saved theme
    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            document.body.className = savedTheme + '-theme';
        }

        // Auto-expand first stack trace item
        const firstItem = document.querySelector('.stack-trace-item');
        if (firstItem) {
            firstItem.classList.add('expanded');
        }
    });
    </script>
</body>

</html>