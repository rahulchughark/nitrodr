<?php
include('includes/header.php');
?>
<style>
    #page-topbar, .topnav, .sidebar-footer {
        display: none;
    }
</style>

<div class="whatsapp-main-frame">
    <div class="minisidebar">
        <div class="miniSidebarInner">
            <div class="miniSidebarTop">
                <!-- chat Icon -->
                 <button>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M6.45455 19L2 22.5V4C2 3.44772 2.44772 3 3 3H21C21.5523 3 22 3.44772 22 4V18C22 18.5523 21.5523 19 21 19H6.45455ZM8 10V12H16V10H8Z"></path></svg>
                </button>
                <!-- status icon -->
                <button>
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M2.45001 14.97C3.52001 18.41 6.40002 21.06 9.98002 21.79" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M2.04999 10.98C2.55999 5.93 6.81998 2 12 2C17.18 2 21.44 5.94 21.95 10.98" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M14.01 21.8C17.58 21.07 20.45 18.45 21.54 15.02" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                </button>
                <!-- Channels -->
                 <!-- <button>
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M2.45001 14.97C3.52001 18.41 6.40002 21.06 9.98002 21.79" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M2.04999 10.98C2.55999 5.93 6.81998 2 12 2C17.18 2 21.44 5.94 21.95 10.98" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M14.01 21.8C17.58 21.07 20.45 18.45 21.54 15.02" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                </button> -->

                <!-- Groups Icon -->
                 <button>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M2 22C2 17.5817 5.58172 14 10 14C14.4183 14 18 17.5817 18 22H2ZM10 13C6.685 13 4 10.315 4 7C4 3.685 6.685 1 10 1C13.315 1 16 3.685 16 7C16 10.315 13.315 13 10 13ZM17.3628 15.2332C20.4482 16.0217 22.7679 18.7235 22.9836 22H20C20 19.3902 19.0002 17.0139 17.3628 15.2332ZM15.3401 12.9569C16.9728 11.4922 18 9.36607 18 7C18 5.58266 17.6314 4.25141 16.9849 3.09687C19.2753 3.55397 21 5.57465 21 8C21 10.7625 18.7625 13 16 13C15.7763 13 15.556 12.9853 15.3401 12.9569Z"></path></svg>
                 </button>
            </div>
            <div class="miniSidebarTop">
                <button>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M8.68637 4.00008L11.293 1.39348C11.6835 1.00295 12.3167 1.00295 12.7072 1.39348L15.3138 4.00008H19.0001C19.5524 4.00008 20.0001 4.4478 20.0001 5.00008V8.68637L22.6067 11.293C22.9972 11.6835 22.9972 12.3167 22.6067 12.7072L20.0001 15.3138V19.0001C20.0001 19.5524 19.5524 20.0001 19.0001 20.0001H15.3138L12.7072 22.6067C12.3167 22.9972 11.6835 22.9972 11.293 22.6067L8.68637 20.0001H5.00008C4.4478 20.0001 4.00008 19.5524 4.00008 19.0001V15.3138L1.39348 12.7072C1.00295 12.3167 1.00295 11.6835 1.39348 11.293L4.00008 8.68637V5.00008C4.00008 4.4478 4.4478 4.00008 5.00008 4.00008H8.68637ZM6.00008 6.00008V9.5148L3.5148 12.0001L6.00008 14.4854V18.0001H9.5148L12.0001 20.4854L14.4854 18.0001H18.0001V14.4854L20.4854 12.0001L18.0001 9.5148V6.00008H14.4854L12.0001 3.5148L9.5148 6.00008H6.00008ZM12.0001 16.0001C9.79094 16.0001 8.00008 14.2092 8.00008 12.0001C8.00008 9.79094 9.79094 8.00008 12.0001 8.00008C14.2092 8.00008 16.0001 9.79094 16.0001 12.0001C16.0001 14.2092 14.2092 16.0001 12.0001 16.0001ZM12.0001 14.0001C13.1047 14.0001 14.0001 13.1047 14.0001 12.0001C14.0001 10.8955 13.1047 10.0001 12.0001 10.0001C10.8955 10.0001 10.0001 10.8955 10.0001 12.0001C10.0001 13.1047 10.8955 14.0001 12.0001 14.0001Z"></path></svg>
                </button>
                <button class="wtsUserProfile">
                    <img src="images/wtsUser.png" alt="">
                </button>
            </div>
        </div>
    </div>
    <div class="wtsSidebar">
        <div class="wtsTop">
            <h3>WhatsApp</h3>
            <button>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M14 3V5H4V18.3851L5.76282 17H20V10H22V18C22 18.5523 21.5523 19 21 19H6.45455L2 22.5V4C2 3.44772 2.44772 3 3 3H14ZM19 3V0H21V3H24V5H21V8H19V5H16V3H19Z"></path></svg>
            </button>
            <button>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3C10.9 3 10 3.9 10 5C10 6.1 10.9 7 12 7C13.1 7 14 6.1 14 5C14 3.9 13.1 3 12 3ZM12 17C10.9 17 10 17.9 10 19C10 20.1 10.9 21 12 21C13.1 21 14 20.1 14 19C14 17.9 13.1 17 12 17ZM12 10C10.9 10 10 10.9 10 12C10 13.1 10.9 14 12 14C13.1 14 14 13.1 14 12C14 10.9 13.1 10 12 10Z"></path></svg>
            </button>
        </div>
        <div class="wtsSearchBar">
           <svg viewBox="0 0 24 24" fill="none"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.72"> <path d="M15 15L21 21" stroke="" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="" stroke-width="2"></path> </g><g id="SVGRepo_iconCarrier"> <path d="M15 15L21 21" stroke="" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="" stroke-width="2"></path> </g></svg>
            <input type="text" placeholder="Search or start new chat">
        </div>
        <div class="wtsChatList">
            <div class="wtsChatListInner">
                <h3 class="wtsChatTitle">Chats</h3>
                <div class="wtsChatItem" data-user="John Doe" data-img="images/wtsUser.png">
                    <img src="images/wtsUser.png" alt="John">
                    <div class="wtsChatInfo">
                        <h4>
                            John Doe
                            <div class="wtsChatTime">11:47am</div>
                        </h4>
                        <p><span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M11.602 13.7599L13.014 15.1719L21.4795 6.7063L22.8938 8.12051L13.014 18.0003L6.65 11.6363L8.06421 10.2221L10.189 12.3469L11.6025 13.7594L11.602 13.7599ZM11.6037 10.9322L16.5563 5.97949L17.9666 7.38977L13.014 12.3424L11.6037 10.9322ZM8.77698 16.5873L7.36396 18.0003L1 11.6363L2.41421 10.2221L3.82723 11.6352L3.82604 11.6363L8.77698 16.5873Z"></path></svg></span> Hey, how are you?</p>
                    </div>
                    
                </div>
                <div class="wtsChatItem" data-user="Jane Smith" data-img="images/wtsUser.png">
                    <img src="images/wtsUser.png" alt="Jane">
                    <div class="wtsChatInfo">
                    <h4>
                        Jane Smith
                        <div class="wtsChatTime">11:47am</div>
                    </h4>
                    <p><span class="MessageSeen"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M11.602 13.7599L13.014 15.1719L21.4795 6.7063L22.8938 8.12051L13.014 18.0003L6.65 11.6363L8.06421 10.2221L10.189 12.3469L11.6025 13.7594L11.602 13.7599ZM11.6037 10.9322L16.5563 5.97949L17.9666 7.38977L13.014 12.3424L11.6037 10.9322ZM8.77698 16.5873L7.36396 18.0003L1 11.6363L2.41421 10.2221L3.82723 11.6352L3.82604 11.6363L8.77698 16.5873Z"></path></svg></span> Let’s meet tomorrow!</p>
                    </div>
                </div>
                <div class="wtsChatItem" data-user="John Doe" data-img="images/wtsUser.png">
                    <img src="images/wtsUser.png" alt="John">
                    <div class="wtsChatInfo">
                        <h4>
                            John Doe
                            <div class="wtsChatTime">21/08/2025</div>
                        </h4>
                        <p>Hey, how are you?</p>
                    </div>
                    
                </div>
                <div class="wtsChatItem" data-user="Jane Smith" data-img="images/wtsUser.png">
                    <img src="images/wtsUser.png" alt="Jane">
                    <div class="wtsChatInfo">
                    <h4>
                        Jane Smith
                        <div class="wtsChatTime">21/08/2025</div>
                    </h4>
                    <p>Let’s meet tomorrow!</p>
                    </div>
                </div>
                <div class="wtsChatItem" data-user="John Doe" data-img="images/wtsUser.png">
                    <img src="images/wtsUser.png" alt="John">
                    <div class="wtsChatInfo">
                        <h4>
                            John Doe
                            <div class="wtsChatTime">21/08/2025</div>
                        </h4>
                        <p>Hey, how are you?</p>
                    </div>
                    
                </div>
                <div class="wtsChatItem" data-user="Jane Smith" data-img="images/wtsUser.png">
                    <img src="images/wtsUser.png" alt="Jane">
                    <div class="wtsChatInfo">
                    <h4>
                        Jane Smith
                        <div class="wtsChatTime">21/08/2025</div>
                    </h4>
                    <p>Let’s meet tomorrow!</p>
                    </div>
                </div>
                <div class="wtsChatItem" data-user="John Doe" data-img="images/wtsUser.png">
                    <img src="images/wtsUser.png" alt="John">
                    <div class="wtsChatInfo">
                        <h4>
                            John Doe
                            <div class="wtsChatTime">21/08/2025</div>
                        </h4>
                        <p>Hey, how are you?</p>
                    </div>
                    
                </div>
                <div class="wtsChatItem" data-user="Jane Smith" data-img="images/wtsUser.png">
                    <img src="images/wtsUser.png" alt="Jane">
                    <div class="wtsChatInfo">
                    <h4>
                        Jane Smith
                        <div class="wtsChatTime">21/08/2025</div>
                    </h4>
                    <p>Let’s meet tomorrow!</p>
                    </div>
                </div>
                <div class="wtsChatItem" data-user="John Doe" data-img="images/wtsUser.png">
                    <img src="images/wtsUser.png" alt="John">
                    <div class="wtsChatInfo">
                        <h4>
                            John Doe
                            <div class="wtsChatTime">21/08/2025</div>
                        </h4>
                        <p>Hey, how are you?</p>
                    </div>
                    
                </div>
                <div class="wtsChatItem" data-user="Jane Smith" data-img="images/wtsUser.png">
                    <img src="images/wtsUser.png" alt="Jane">
                    <div class="wtsChatInfo">
                    <h4>
                        Jane Smith
                        <div class="wtsChatTime">21/08/2025</div>
                    </h4>
                    <p>Let’s meet tomorrow!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="WtsChatArea">
        <div class="wtsChatHeader">
            <div class="d-flex align-items-center">
                <button class="back-btn mr-1" style="display:none;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M7.82843 10.9999H20V12.9999H7.82843L13.1924 18.3638L11.7782 19.778L4 11.9999L11.7782 4.22168L13.1924 5.63589L7.82843 10.9999Z"></path></svg>
                </button>
                <div class="user">
                    <img src="images/wtsUser.png" id="chat-user-img" alt="User">
                    <h4 id="chat-user-name">John Doe</h4>
                </div>
            </div>
            <div class="icons">
                <button>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M18.031 16.6168L22.3137 20.8995L20.8995 22.3137L16.6168 18.031C15.0769 19.263 13.124 20 11 20C6.032 20 2 15.968 2 11C2 6.032 6.032 2 11 2C15.968 2 20 6.032 20 11C20 13.124 19.263 15.0769 18.031 16.6168ZM16.0247 15.8748C17.2475 14.6146 18 12.8956 18 11C18 7.1325 14.8675 4 11 4C7.1325 4 4 7.1325 4 11C4 14.8675 7.1325 18 11 18C12.8956 18 14.6146 17.2475 15.8748 16.0247L16.0247 15.8748Z"></path></svg>
                </button>
                
                <button>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3C10.9 3 10 3.9 10 5C10 6.1 10.9 7 12 7C13.1 7 14 6.1 14 5C14 3.9 13.1 3 12 3ZM12 17C10.9 17 10 17.9 10 19C10 20.1 10.9 21 12 21C13.1 21 14 20.1 14 19C14 17.9 13.1 17 12 17ZM12 10C10.9 10 10 10.9 10 12C10 13.1 10.9 14 12 14C13.1 14 14 13.1 14 12C14 10.9 13.1 10 12 10Z"></path></svg>
                </button>
            </div>
        </div>
        <div class="wtsChatMessages" id="wtsChatMessages">
            <div class="message received">
                <div class="message-inner">
                    <div>Hello 👋</div>  
                    <span class="curve"></span>
                    <span class="timestamp">10:30 AM</span>
                </div>
            </div>
            <div class="message sent">
                <div class="message-inner">
                    <div>Hi there 😃</div>
                    <span class="timestamp">10:32 AM</span>
                </div>
            </div>
        </div>
        <div class="wtsChatInput">
            <button><i class="fa fa-smile"></i></button>
            <button><i class="fa fa-paperclip"></i></button>
            <div class="wtsInputBox">
                <input type="text" id="message-input" placeholder="Type a message">
            </div>
             <!-- <div class="wtsMessageBox" contenteditable="true" role="textbox" 
                    aria-multiline="true" spellcheck="true" 
                    data-placeholder="Type a message">
            </div> -->
            <button id="send-btn"><i class="fa fa-paper-plane"></i></button>
        </div>
    </div>
</div>

<script>
// document.addEventListener("DOMContentLoaded", function () {
//     const chatItems = document.querySelectorAll(".wtsChatItem");
//     const chatUserImg = document.getElementById("chat-user-img");
//     const chatUserName = document.getElementById("chat-user-name");
//     const chatMessages = document.getElementById("wtsChatMessages");
//     const messageInput = document.getElementById("message-input");
//     const sendBtn = document.getElementById("send-btn");

//     // ✅ Store messages for each user
//     let chatHistory = {};

//     let activeChat = null;

//     // ✅ Switch chat when clicked
//     chatItems.forEach(item => {
//         item.addEventListener("click", function () {
//             // Remove active from all
//             chatItems.forEach(c => c.classList.remove("active"));
//             // Set active to clicked one
//             this.classList.add("active");

//             const userName = this.dataset.user;
//             const userImg = this.dataset.img;

//             // Change chat header
//             chatUserName.textContent = userName;
//             chatUserImg.src = userImg;

//             activeChat = userName;

//             // Load messages for this chat
//             chatMessages.innerHTML = "";
//             if (chatHistory[userName]) {
//                 chatHistory[userName].forEach(msgHTML => {
//                     chatMessages.innerHTML += msgHTML;
//                 });
//             } else {
//                 // First time chatting → start history
//                 chatHistory[userName] = [
//                     `
//                     <div class="message received">
//                         <div class="message-inner">
//                             <div>Hello 👋</div>
//                             <span class="curve"></span>
//                             <span class="timestamp">10:30 AM</span>
//                         </div>
//                     </div>
//                     `
//                 ];
//                 chatMessages.innerHTML = chatHistory[userName].join("");
//             }

//             chatMessages.scrollTop = chatMessages.scrollHeight;
//         });
//     });

//     // ✅ Send message function
//     function sendMessage() {
//         if (!activeChat) return; // No chat selected

//         const msg = messageInput.value.trim();
//         if (msg === "") return;

//         const msgHTML = `
//             <div class="message sent">
//                 <div class="message-inner">
//                     <div>${msg}</div>
//                     <span class="timestamp">${new Date().toLocaleTimeString([], {hour: "2-digit", minute: "2-digit"})}</span>
//                 </div>
//             </div>
//         `;

//         // Save message in history
//         chatHistory[activeChat].push(msgHTML);

//         // Render in chat
//         chatMessages.innerHTML += msgHTML;

//         messageInput.value = "";
//         chatMessages.scrollTop = chatMessages.scrollHeight;
//     }

//     // ✅ Click send
//     sendBtn.addEventListener("click", sendMessage);

//     // ✅ Press Enter to send
//     messageInput.addEventListener("keypress", function (e) {
//         if (e.key === "Enter") {
//             e.preventDefault();
//             sendMessage();
//         }
//     });
// });/

document.addEventListener("DOMContentLoaded", function () {
    const chatItems = document.querySelectorAll(".wtsChatItem");
    const chatUserImg = document.getElementById("chat-user-img");
    const chatUserName = document.getElementById("chat-user-name");
    const chatMessages = document.getElementById("wtsChatMessages");
    const messageInput = document.getElementById("message-input");
    const sendBtn = document.getElementById("send-btn");

    const chatList = document.querySelector(".wtsChatList");
    const chatArea = document.querySelector(".WtsChatArea");
    const backBtn = document.querySelector(".back-btn");

    let chatHistory = {};
    let activeChat = null;

    chatItems.forEach(item => {
        item.addEventListener("click", function () {
            chatItems.forEach(c => c.classList.remove("active"));
            this.classList.add("active");

            const userName = this.dataset.user;
            const userImg = this.dataset.img;

            chatUserName.textContent = userName;
            chatUserImg.src = userImg;
            activeChat = userName;

            chatMessages.innerHTML = chatHistory[userName]
                ? chatHistory[userName].join("")
                : `
                <div class="message received">
                    <div class="message-inner">
                        <div>Hello 👋</div>
                        <span class="timestamp">10:30 AM</span>
                    </div>
                </div>
                `;
            if (!chatHistory[userName]) chatHistory[userName] = [chatMessages.innerHTML];

            chatMessages.scrollTop = chatMessages.scrollHeight;

            // ✅ Mobile → slide in chat window
            if (window.innerWidth <= 768) {
                chatArea.classList.add("active");
                backBtn.style.display = "inline-flex";
            }
        });
    });

    function sendMessage() {
        if (!activeChat) return;
        const msg = messageInput.value.trim();
        if (!msg) return;

        const msgHTML = `
            <div class="message sent">
                <div class="message-inner">
                    <div>${msg}</div>
                    <span class="timestamp">${new Date().toLocaleTimeString([], {hour: "2-digit", minute: "2-digit"})}</span>
                </div>
            </div>
        `;
        chatHistory[activeChat].push(msgHTML);
        chatMessages.innerHTML += msgHTML;
        messageInput.value = "";
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    sendBtn.addEventListener("click", sendMessage);
    messageInput.addEventListener("keypress", e => {
        if (e.key === "Enter") {
            e.preventDefault();
            sendMessage();
        }
    });

    // ✅ Back button → slide chat window out
    backBtn.addEventListener("click", function () {
        chatArea.classList.remove("active");
        backBtn.style.display = "none";
    });
});

</script>


