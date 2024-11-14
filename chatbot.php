<div class="container">
        <!-- Chatbox -->
        <!-- Chatbox -->
        <div class="chatbox card" id="chatbox" style="display:none;">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>💇‍♂️ Asistente de Barbería</strong>
                    </div>
                    <button id="closeChat" class="close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="chatlogs card-body" id="chatlogs">
                <!-- Los mensajes se agregarán aquí dinámicamente -->
            </div>
            <div class="typing-field">
                <div class="input-data">
                    <input type="text" id="userInput" class="form-control"
                        placeholder="Escribe 'reservar cita' para comenzar..." required>
                    <button id="sendBtn" class="btn btn-primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Botón flotante -->
        <button id="floatingBtn" class="btn btn-floating">
            💬
        </button>
    </div>
