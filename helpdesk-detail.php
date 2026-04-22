<?php
include('includes/header.php');
?>

<style>
    @media (max-width: 767px) {
        body[data-layout=horizontal] .page-content {
            padding-bottom: 0;
        }
    }
</style>
<div class="main-content drHelpdesk">
    <div class="page-content">
        <div class="container py-md-3">
            <div class="row d-flex justify-content-center">
                <div class="col-md-12 col-lg-12 col-xl-12">
                    <div class="row align-items-center mb-3">
                        <div class="col">
                            <h1 class="helpdesk-title mb-0">Helpdesk AI Chatbot</h1>
                        </div>
                    </div>
                    <div class="card mt-2 bg-transparent">
                        <div class="card-body main-card-body">
                            <div id="chat-box" style="height: 280px" class="overflow-auto p-md-3">
                                <div class="message user">
                                    <div class="messageTime">11/8/2024, 9:52 AM</div>
                                    <div class="content">
                                        <p class="user-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
                                        <div class="userIcon"><img src="images/user.jpg" alt=""></div>
                                    </div>
                                </div>
                                <div class="message bot">
                                    <div class="showTime">11/8/2024, 9:52 AM</div>
                                    <div class="botMessage">
                                        <div class="botIcon"><img src="images/wtsUser.png" alt=""></div>
                                        <p class="bot-text">
                                            Hello! How can I assist you today?<br>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div id="loading-spinner" class="text-center" style="display:none;">
                                <div class="spinner-border text-success" role="status"></div>
                                <p class="mt-2">Fetching response...</p>
                            </div>
                            <div class="form-outline" id="text-query-field">
                                <form id="ask-chatgpt-form" class="ask-chatgpt-form">
                                    <input type="hidden" name="_token" >
                                    <textarea class="form-control" 
                                              name="prompt" 
                                              placeholder="Type your question..." 
                                              required 
                                              style="height: 64px;"></textarea>
                                    <button type="submit" class="btn btn-send">
                                        <svg xmlns="http://www.w3.org/2000/svg" 
                                             viewBox="0 0 24 24" 
                                             fill="currentColor">
                                            <path d="M1.94619 9.31543C1.42365 9.14125 1.41953 8.86022 1.95694 8.68108L21.0431 2.31901C21.5716 2.14285 21.8747 2.43866 21.7266 2.95694L16.2734 22.0432C16.1224 22.5716 15.8178 22.59 15.5945 22.0876L12 14L18 6.00005L10 12L1.94619 9.31543Z"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
