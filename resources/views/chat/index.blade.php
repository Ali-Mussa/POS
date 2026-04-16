@extends('layouts.master')

@section('title')
    Chat
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Chat</li>
@endsection

@push('css')
<style>
    .chat-wrapper {
        display: flex;
        height: calc(100vh - 180px);
        min-height: 500px;
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        overflow: hidden;
    }

    /* ── Left Contacts Panel ── */
    .chat-contacts {
        width: 320px;
        min-width: 280px;
        border-right: 1px solid #e4e7ea;
        display: flex;
        flex-direction: column;
        background: #fafbfc;
    }
    .contacts-header {
        padding: 18px 20px;
        border-bottom: 1px solid #e4e7ea;
        background: #fff;
    }
    .contacts-header h4 {
        margin: 0 0 10px;
        font-weight: 600;
        color: #333;
    }
    .contacts-search {
        position: relative;
    }
    .contacts-search input {
        width: 100%;
        padding: 8px 12px 8px 34px;
        border: 1px solid #ddd;
        border-radius: 20px;
        font-size: 13px;
        outline: none;
        transition: border-color 0.2s;
    }
    .contacts-search input:focus {
        border-color: #00a65a;
    }
    .contacts-search .fa {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #aaa;
    }
    .contacts-list {
        flex: 1;
        overflow-y: auto;
        padding: 8px 0;
    }
    .contact-item {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        cursor: pointer;
        transition: background 0.15s;
        border-left: 3px solid transparent;
    }
    .contact-item:hover {
        background: #f0f2f5;
    }
    .contact-item.active {
        background: #e8f5e9;
        border-left-color: #00a65a;
    }
    .contact-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #00a65a;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 16px;
        flex-shrink: 0;
        overflow: hidden;
    }
    .contact-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .contact-info {
        margin-left: 12px;
        flex: 1;
        min-width: 0;
    }
    .contact-name {
        font-weight: 600;
        font-size: 14px;
        color: #333;
        margin-bottom: 2px;
    }
    .contact-preview {
        font-size: 12px;
        color: #999;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .contact-meta {
        text-align: right;
        flex-shrink: 0;
        margin-left: 8px;
    }
    .contact-time {
        font-size: 11px;
        color: #aaa;
        display: block;
        margin-bottom: 4px;
    }
    .unread-badge {
        background: #00a65a;
        color: #fff;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 600;
    }

    /* ── Right Messages Panel ── */
    .chat-messages-panel {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #fff;
    }
    .messages-header {
        padding: 15px 20px;
        border-bottom: 1px solid #e4e7ea;
        display: flex;
        align-items: center;
        background: #fff;
        min-height: 64px;
    }
    .messages-header .contact-avatar {
        width: 36px;
        height: 36px;
        font-size: 14px;
    }
    .messages-header-info {
        margin-left: 12px;
    }
    .messages-header-info h5 {
        margin: 0;
        font-weight: 600;
        color: #333;
    }
    .messages-header-info small {
        color: #999;
    }
    .chat-empty-state {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #bbb;
    }
    .chat-empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        color: #ddd;
    }
    .chat-empty-state h4 {
        color: #999;
        font-weight: 400;
    }
    .messages-body {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        background: #f5f7fa;
    }
    .message-date-divider {
        text-align: center;
        margin: 16px 0;
    }
    .message-date-divider span {
        background: #e4e7ea;
        color: #888;
        padding: 4px 14px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 500;
    }
    .message-bubble {
        max-width: 70%;
        margin-bottom: 8px;
        padding: 10px 14px;
        border-radius: 16px;
        font-size: 14px;
        line-height: 1.5;
        position: relative;
        word-wrap: break-word;
        clear: both;
    }
    .message-bubble.sent {
        background: #00a65a;
        color: #fff;
        margin-left: auto;
        border-bottom-right-radius: 4px;
    }
    .message-bubble.received {
        background: #fff;
        color: #333;
        margin-right: auto;
        border-bottom-left-radius: 4px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.08);
    }
    .message-time {
        font-size: 10px;
        margin-top: 4px;
        text-align: right;
    }
    .message-bubble.sent .message-time {
        color: rgba(255,255,255,0.75);
    }
    .message-bubble.received .message-time {
        color: #aaa;
    }
    .messages-input {
        padding: 14px 20px;
        border-top: 1px solid #e4e7ea;
        display: flex;
        align-items: center;
        gap: 10px;
        background: #fff;
    }
    .messages-input input {
        flex: 1;
        padding: 10px 16px;
        border: 1px solid #ddd;
        border-radius: 24px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s;
    }
    .messages-input input:focus {
        border-color: #00a65a;
    }
    .messages-input button {
        width: 42px;
        height: 42px;
        border: none;
        background: #00a65a;
        color: #fff;
        border-radius: 50%;
        cursor: pointer;
        font-size: 16px;
        transition: background 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .messages-input button:hover {
        background: #008d4c;
    }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .chat-contacts { width: 100%; }
        .chat-wrapper { flex-direction: column; height: auto; }
        .chat-messages-panel { min-height: 400px; }
    }
</style>
@endpush

@section('content')
<div class="chat-wrapper" id="chatApp">
    {{-- Left Panel: Contacts --}}
    <div class="chat-contacts">
        <div class="contacts-header">
            <h4><i class="fa fa-comments"></i> Messages</h4>
            <div class="contacts-search">
                <i class="fa fa-search"></i>
                <input type="text" id="searchContacts" placeholder="Search contacts...">
            </div>
        </div>
        <div class="contacts-list" id="contactsList">
            <div style="text-align:center; padding:40px; color:#aaa;">
                <i class="fa fa-spinner fa-spin fa-2x"></i>
                <p>Loading contacts...</p>
            </div>
        </div>
    </div>

    {{-- Right Panel: Messages --}}
    <div class="chat-messages-panel">
        {{-- Empty State (shown when no conversation selected) --}}
        <div class="chat-empty-state" id="emptyState">
            <i class="fa fa-comments-o"></i>
            <h4>Select a contact to start chatting</h4>
        </div>

        {{-- Chat Header (hidden initially) --}}
        <div class="messages-header" id="chatHeader" style="display:none;">
            <div class="contact-avatar" id="chatAvatar"></div>
            <div class="messages-header-info">
                <h5 id="chatUserName"></h5>
                <small id="chatUserEmail"></small>
            </div>
        </div>

        {{-- Messages Body --}}
        <div class="messages-body" id="messagesBody" style="display:none;"></div>

        {{-- Input Area --}}
        <div class="messages-input" id="messageInputArea" style="display:none;">
            <input type="text" id="messageInput" placeholder="Type a message..." autocomplete="off">
            <button type="button" id="sendBtn" title="Send">
                <i class="fa fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    var currentUserId = null;
    var currentUserName = '';
    var pollInterval = null;
    var authUserId = {{ auth()->id() }};

    // ── Load Contacts ──
    function loadContacts() {
        $.get("{{ route('chat.contacts') }}", function(contacts) {
            var html = '';
            if (contacts.length === 0) {
                html = '<div style="text-align:center;padding:40px;color:#aaa;"><i class="fa fa-user-times fa-2x"></i><p>No contacts available</p></div>';
            }
            $.each(contacts, function(i, c) {
                var initials = c.name.split(' ').map(function(n){ return n[0]; }).join('').substring(0,2).toUpperCase();
                var roleColor = c.level == 1 ? '#3c8dbc' : (c.level == 2 ? '#f39c12' : '#00a65a');
                var roleBadge = '<span style="background:' + roleColor + ';color:#fff;font-size:10px;padding:1px 6px;border-radius:10px;margin-left:5px;">' + c.role + '</span>';
                var preview = c.last_message
                    ? c.last_message.substring(0, 35) + (c.last_message.length > 35 ? '...' : '')
                    : '<em style="color:#bbb;">' + c.role + ' · ' + c.email + '</em>';
                var timeStr = c.last_time || '';
                var badge = c.unread_count > 0 ? '<span class="unread-badge">' + c.unread_count + '</span>' : '';
                var activeClass = c.id == currentUserId ? ' active' : '';

                html += '<div class="contact-item' + activeClass + '" data-id="' + c.id + '" data-name="' + c.name + '" data-email="' + c.email + '" data-role="' + c.role + '">';
                html += '  <div class="contact-avatar" style="background:' + roleColor + '">' + initials + '</div>';
                html += '  <div class="contact-info">';
                html += '    <div class="contact-name">' + c.name + roleBadge + '</div>';
                html += '    <div class="contact-preview">' + preview + '</div>';
                html += '  </div>';
                html += '  <div class="contact-meta">';
                html += '    <span class="contact-time">' + timeStr + '</span>';
                html += '    ' + badge;
                html += '  </div>';
                html += '</div>';
            });
            $('#contactsList').html(html);
        });
    }

    loadContacts();

    // ── Search filter ──
    $(document).on('keyup', '#searchContacts', function() {
        var term = $(this).val().toLowerCase();
        $('#contactsList .contact-item').each(function() {
            var name = $(this).data('name').toLowerCase();
            $(this).toggle(name.indexOf(term) > -1);
        });
    });

    // ── Select Contact ──
    $(document).on('click', '.contact-item', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var email = $(this).data('email');

        currentUserId = id;
        currentUserName = name;

        // Update UI
        $('.contact-item').removeClass('active');
        $(this).addClass('active');

        var initials = name.split(' ').map(function(n){ return n[0]; }).join('').substring(0,2).toUpperCase();
        $('#chatAvatar').text(initials);
        $('#chatUserName').text(name);
        $('#chatUserEmail').text(email);

        $('#emptyState').hide();
        $('#chatHeader, #messagesBody, #messageInputArea').show();

        loadMessages();

        // Start polling
        if (pollInterval) clearInterval(pollInterval);
        pollInterval = setInterval(function() {
            loadMessages(true);
            loadContacts();
        }, 3000);

        $('#messageInput').focus();
    });

    // ── Load Messages ──
    function loadMessages(silent) {
        if (!currentUserId) return;

        $.get("{{ url('/chat/messages') }}/" + currentUserId, function(messages) {
            var html = '';
            var lastDate = '';

            $.each(messages, function(i, msg) {
                // Date divider
                if (msg.date !== lastDate) {
                    html += '<div class="message-date-divider"><span>' + msg.date + '</span></div>';
                    lastDate = msg.date;
                }

                var cls = msg.is_mine ? 'sent' : 'received';
                html += '<div class="message-bubble ' + cls + '">';
                html += '  <div>' + escapeHtml(msg.message) + '</div>';
                html += '  <div class="message-time">' + msg.time + '</div>';
                html += '</div>';
            });

            if (messages.length === 0) {
                html = '<div style="text-align:center;padding:40px;color:#bbb;"><i class="fa fa-comment-o fa-3x"></i><p>No messages yet. Say hello! 👋</p></div>';
            }

            var body = document.getElementById('messagesBody');
            var wasAtBottom = (body.scrollTop + body.clientHeight >= body.scrollHeight - 50);

            $('#messagesBody').html(html);

            // Auto-scroll to bottom (only if already at bottom or first load)
            if (!silent || wasAtBottom) {
                body.scrollTop = body.scrollHeight;
            }
        });
    }

    // ── Send Message ──
    function sendMessage() {
        var msg = $('#messageInput').val().trim();
        if (!msg || !currentUserId) return;

        $('#messageInput').val('');
        $('#sendBtn').prop('disabled', true);

        $.ajax({
            url: "{{ route('chat.send') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                receiver_id: currentUserId,
                message: msg
            },
            success: function(response) {
                loadMessages();
                loadContacts();
                $('#sendBtn').prop('disabled', false);
                $('#messageInput').focus();
            },
            error: function(xhr) {
                alert('Failed to send message. Please try again.');
                $('#messageInput').val(msg);
                $('#sendBtn').prop('disabled', false);
            }
        });
    }

    $('#sendBtn').on('click', sendMessage);

    $('#messageInput').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            sendMessage();
        }
    });

    // ── Utility ──
    function escapeHtml(text) {
        var map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
});
</script>
@endpush
