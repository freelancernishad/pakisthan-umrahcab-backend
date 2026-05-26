<!-- Global Code Example Modal -->
<div id="code-modal" class="fixed inset-0 z-[70] hidden overflow-y-auto">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/80 backdrop-blur-md" onclick="closeCodeModal()"></div>
        <div class="relative w-full max-w-5xl">
            <div class="glass rounded-[2rem] shadow-2xl overflow-hidden border border-white/10">
                <!-- Modal Header -->
                <div class="p-6 border-b border-white/5 bg-white/5 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                            Code Integration Details
                        </h3>
                        <p id="code-modal-url" class="text-xs text-slate-500 font-mono mt-1"></p>
                    </div>
                    <button onclick="closeCodeModal()" class="p-2 rounded-xl bg-white/5 text-slate-400 hover:text-white hover:bg-white/10 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Prism Theme -->
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css">
                    <style>
                        /* Custom Prism Overrides */
                        pre[class*="language-"] {
                            background: rgba(0, 0, 0, 0.6) !important;
                            border: 1px solid rgba(255, 255, 255, 0.05) !important;
                            border-radius: 1rem !important;
                            margin: 0 !important;
                            padding: 1.5rem !important;
                            min-height: 300px;
                        }
                        code[class*="language-"] {
                            text-shadow: none !important;
                            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
                        }
                    </style>

                    <!-- Language Switcher -->
                    <div class="flex flex-wrap p-1 bg-black/40 rounded-2xl w-fit border border-white/5 gap-1">
                        <button onclick="switchLanguage('docs')" class="lang-btn px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-white transition-all" data-lang="docs">Integration</button>
                        <button onclick="switchLanguage('try')" class="lang-btn px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-white transition-all ring-1 ring-indigo-500/50" data-lang="try">Try Now</button>
                        <button onclick="switchLanguage('js')" class="lang-btn px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-white transition-all" data-lang="js">JavaScript</button>
                        <button onclick="switchLanguage('react')" class="lang-btn px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-white transition-all" data-lang="react">React / Next.js</button>
                        <button onclick="switchLanguage('rtk')" class="lang-btn px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-white transition-all" data-lang="rtk">RTK Query</button>
                        <button onclick="switchLanguage('php')" class="lang-btn px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-white transition-all" data-lang="php">PHP (cURL)</button>
                        <button onclick="switchLanguage('curl')" class="lang-btn px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-white transition-all" data-lang="curl">cURL</button>
                    </div>

                    <!-- Code Display -->
                    <div id="code-section" class="relative group">
                        <button onclick="copyCodeContent(this)" class="absolute top-4 right-4 p-2.5 rounded-xl bg-white/5 text-slate-500 hover:text-white transition-all border border-white/10 z-10 backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 icon-copy" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500 hidden icon-check" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                        <pre id="code-pre" class="language-javascript"><code id="code-display" class="language-javascript"></code></pre>
                    </div>
                    
                    <div id="try-section" class="hidden space-y-8">
                        <!-- Request Flow (Full Width) -->
                        <div class="space-y-6">
                            <!-- Step 1: Endpoint -->
                            <div class="p-6 rounded-3xl bg-white/5 border border-white/10 space-y-4 shadow-inner">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-4 bg-indigo-500 rounded-full"></div>
                                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Target Endpoint</label>
                                    </div>
                                    <span id="try-method-badge" class="px-3 py-1 rounded-xl text-[10px] font-black uppercase bg-indigo-500/20 text-indigo-400 border border-indigo-500/20">GET</span>
                                </div>
                                <div class="flex items-center gap-3 bg-black/40 p-3 rounded-2xl font-mono text-xs text-indigo-200 break-all border border-white/5 group/url relative">
                                    <span id="try-url" class="select-all">...</span>
                                    <button onclick="copyToClipboard(document.getElementById('try-url').innerText, this)" class="opacity-0 group-hover/url:opacity-100 transition-opacity p-1.5 rounded-lg bg-white/10 text-white hover:bg-white/20 ml-auto flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 icon-copy" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-emerald-500 hidden icon-check" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 2: Headers -->
                            <div class="p-6 rounded-3xl bg-white/5 border border-white/10 space-y-4 shadow-inner">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-4 bg-emerald-500 rounded-full"></div>
                                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Request Headers</label>
                                    </div>
                                    <div class="flex items-center gap-1.5 px-2 py-0.5 rounded-lg bg-emerald-500/10 text-emerald-400 border border-emerald-500/10">
                                        <span class="w-1 h-1 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span class="text-[10px] font-bold uppercase">Authenticated</span>
                                    </div>
                                </div>
                                <div class="bg-black/40 border border-white/5 rounded-2xl p-4 font-mono text-[11px] text-slate-400 space-y-2">
                                    <div class="flex justify-between items-center group/token relative">
                                        <span class="text-indigo-400/70 select-none">Authorization</span>
                                        <div class="flex items-center gap-2">
                                            <span class="text-slate-500">Bearer</span>
                                            <div class="relative flex items-center bg-white/5 rounded-lg px-2 py-1 border border-white/10 group/token-box">
                                                <span class="text-slate-400 select-none tracking-widest">••••••••••••</span>
                                                <span id="real-token" class="hidden"></span>
                                                <button onclick="copyAuthToken(this)" class="ml-2 p-1 rounded hover:bg-white/10 text-slate-500 hover:text-white transition-all" title="Copy Token">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center border-t border-white/5 pt-2">
                                        <span class="text-indigo-400/70 select-none">Accept</span>
                                        <span class="text-slate-200">application/json</span>
                                    </div>
                                    <div id="header-content-type" class="flex justify-between items-center">
                                        <span class="text-indigo-400/70 select-none">Content-Type</span>
                                        <span class="text-slate-200">application/json</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3: Payload Body -->
                            <div id="try-body-container" class="p-6 rounded-3xl bg-white/5 border border-white/10 space-y-4 shadow-inner">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-4 bg-amber-500 rounded-full"></div>
                                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Payload Body</label>
                                    </div>
                                    
                                    <!-- Body Type Toggle -->
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center bg-black/40 rounded-xl p-1 border border-white/10">
                                            <button onclick="toggleBodyType('json')" id="btn-type-json" class="px-3 py-1.5 rounded-lg text-[10px] font-black transition-all bg-indigo-500 text-white shadow-lg shadow-indigo-500/20">JSON</button>
                                            <button onclick="toggleBodyType('form')" id="btn-type-form" class="px-3 py-1.5 rounded-lg text-[10px] font-black transition-all text-slate-500 hover:text-white">FORM</button>
                                        </div>
                                        <button onclick="copyRequestBody(this)" class="p-2.5 rounded-xl bg-white/5 text-slate-500 hover:text-white transition-all border border-white/5 group/copy">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 icon-copy" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500 hidden icon-check" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- JSON Editor -->
                                <div id="body-editor-json" class="h-64 relative group">
                                    <div id="ace-request-editor" class="absolute inset-0 w-full h-full rounded-2xl border border-white/5 overflow-hidden"></div>
                                    <textarea id="try-body" class="hidden"></textarea>
                                </div>

                                <!-- Form Data Builder -->
                                <div id="body-editor-form" class="hidden h-64 overflow-y-auto space-y-3 pr-2 scrollbar-thin scrollbar-thumb-white/10">
                                    <div id="form-data-inputs" class="space-y-3"></div>
                                    <button onclick="addFormDataField()" class="w-full py-2.5 rounded-xl border border-dashed border-white/10 text-slate-500 hover:text-white hover:border-white/20 text-[10px] font-black uppercase tracking-wider transition-all flex items-center justify-center gap-2 bg-white/2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Append Field
                                    </button>
                                </div>

                                <button onclick="executeTryRequest()" id="btn-execute" class="w-full py-4 rounded-2xl bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-600 hover:to-violet-700 text-white font-black text-xs uppercase tracking-[0.2em] transition-all shadow-xl shadow-indigo-500/30 flex items-center justify-center gap-3 group active:scale-[0.98]">
                                    <span>Dispatch Request</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Full Width Response Panel -->
                        <div class="space-y-4">
                            <div class="p-1 rounded-3xl bg-white/5 border border-white/10 overflow-hidden shadow-2xl">
                                <div class="p-6 flex items-center justify-between border-b border-white/5 bg-white/2">
                                    <div class="flex items-center gap-4">
                                        <div class="p-2.5 rounded-xl bg-slate-500/10 text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-black text-white uppercase tracking-widest">Network Response</h4>
                                            <p class="text-[10px] text-slate-500 mt-0.5">Live execution output from the server</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span id="try-status" class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase bg-slate-500/20 text-slate-400 border border-white/5">Idle</span>
                                        <button onclick="copyResponseBody(this)" class="p-2.5 rounded-xl bg-white/5 text-slate-500 hover:text-white transition-all border border-white/5 group/copy">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 icon-copy" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500 hidden icon-check" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="relative min-h-[300px] lg:min-h-[400px]">
                                    <div id="ace-response-viewer" class="absolute inset-0 w-full h-full border-0"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Usage Example (Initially Hidden) -->
                    <div id="usage-section" class="hidden space-y-4">
                        <div class="flex items-center gap-2 text-indigo-400">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span class="text-xs font-bold uppercase tracking-widest">Component Usage Example</span>
                        </div>
                        <div class="relative group">
                            <button onclick="copyCodeContent(this, 'usage-display')" class="absolute top-4 right-4 p-2.5 rounded-xl bg-white/5 text-slate-500 hover:text-white transition-all border border-white/10 z-10 backdrop-blur-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 icon-copy" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500 hidden icon-check" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <pre id="usage-pre" class="language-javascript"><code id="usage-display" class="language-javascript"></code></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentCodeData = { method: '', url: '', body: null };
    let currentBodyType = 'json'; // json or form
    let aceRequestEditor = null;
    let aceResponseViewer = null;

    function initAceEditors() {
        if (aceRequestEditor) return;

        // Request Editor
        aceRequestEditor = ace.edit("ace-request-editor");
        aceRequestEditor.setTheme("ace/theme/tomorrow_night");
        aceRequestEditor.session.setMode("ace/mode/json");
        aceRequestEditor.setOptions({
            fontSize: "12px",
            showPrintMargin: false,
            showGutter: true,
            highlightActiveLine: true,
            wrap: true
        });

        // Response Viewer
        aceResponseViewer = ace.edit("ace-response-viewer");
        aceResponseViewer.setTheme("ace/theme/tomorrow_night");
        aceResponseViewer.session.setMode("ace/mode/json");
        aceResponseViewer.setOptions({
            fontSize: "12px",
            readOnly: true,
            showPrintMargin: false,
            showGutter: true,
            highlightActiveLine: false,
            wrap: true
        });
        aceResponseViewer.renderer.setShowGutter(true);
    }

    function showCodeExample(method, url, body = null) {
        currentCodeData = { method, url, body };
        document.getElementById('code-modal-url').innerText = `${method} ${url}`;
        
        // Ensure modal is visible first so Ace can initialize correctly
        document.getElementById('code-modal').classList.remove('hidden');

        // Initialize Ace Editors
        initAceEditors();
        
        // Reset Try Now inputs
        document.getElementById('try-method-badge').innerText = method;
        document.getElementById('try-url').innerText = url;
        document.getElementById('try-status').innerText = 'Ready';
        document.getElementById('try-status').className = "px-2 py-1 rounded-lg text-[10px] font-bold uppercase bg-slate-500/20 text-slate-400";
        
        // Determine Body Type
        let isFormData = false;
        let formattedBody = '';

        if (body) {
            formattedBody = JSON.stringify(body, null, 4);
            // Check for file placeholders specifically
            const bodyStr = JSON.stringify(body);
            if (bodyStr.includes('(File Object)')) {
                isFormData = true;
            }
        }

        // Set Ace Value
        if (aceRequestEditor) {
            aceRequestEditor.setValue(formattedBody, -1); // -1 moves cursor to start
            aceRequestEditor.resize();
        }
        if (aceResponseViewer) {
            aceResponseViewer.setValue('Click "Send Request" to see response...', -1);
            aceResponseViewer.resize();
        }
        
        // Render Form Inputs always (in case user switches)
        renderFormDataInputs(body);
        
        // Auto-switch based on detection
        toggleBodyType(isFormData ? 'form' : 'json');

        switchLanguage('docs');

        // Initialize token display
        const token = getTokenForUrl(url);
        const realTokenEl = document.getElementById('real-token');
        if (realTokenEl) {
            realTokenEl.innerText = token || 'NOT_FOUND';
        }
    }

    function getTokenForUrl(url) {
        let token = getCookie('admin_token');
        if (!token || url.includes('/api/user') || url.includes('/api/auth/user')) {
             const userToken = getCookie('user_token');
             if (userToken) token = userToken;
        }
        return token;
    }

    function toggleTokenVisibility() {
        // No longer used as per user request
    }

    function copyAuthToken(btn) {
        const token = document.getElementById('real-token').innerText;
        if (token && token !== 'NOT_FOUND') {
            copyToClipboard(token, btn);
        }
    }

    function toggleBodyType(type) {
        currentBodyType = type;
        const btnJson = document.getElementById('btn-type-json');
        const btnForm = document.getElementById('btn-type-form');
        const editorJson = document.getElementById('body-editor-json');
        const editorForm = document.getElementById('body-editor-form');
        const headerContentType = document.getElementById('header-content-type');

        if (type === 'json') {
            btnJson.classList.replace('text-slate-400', 'text-white');
            btnJson.classList.replace('hover:text-white', 'bg-indigo-500');
            btnForm.classList.replace('text-white', 'text-slate-400');
            btnForm.classList.replace('bg-indigo-500', 'hover:text-white');
            
            editorJson.classList.remove('hidden');
            editorForm.classList.add('hidden');
            
            headerContentType.innerHTML = '<span class="text-slate-500 select-none">Content-Type:</span> <span>application/json</span>';
            
            if (aceRequestEditor) aceRequestEditor.resize();
        } else {
            btnForm.classList.replace('text-slate-400', 'text-white');
            btnForm.classList.replace('hover:text-white', 'bg-indigo-500');
            btnJson.classList.replace('text-white', 'text-slate-400');
            btnJson.classList.replace('bg-indigo-500', 'hover:text-white');
            
            editorForm.classList.remove('hidden');
            editorJson.classList.add('hidden');
            
            headerContentType.innerHTML = '<span class="text-slate-500 select-none">Content-Type:</span> <span class="text-slate-400 italic">multipart/form-data (Browser set)</span>';
        }
    }

    function renderFormDataInputs(body) {
        const container = document.getElementById('form-data-inputs');
        container.innerHTML = '';
        
        if (!body) return;

        Object.entries(body).forEach(([key, value]) => {
            addFormDataField(key, value);
        });
    }

    function copyRequestBody(btn) {
        let content = '';

        if (currentBodyType === 'json') {
            content = aceRequestEditor ? aceRequestEditor.getValue() : '';
        } else {
            // Generate Key-Value pairs for FormData
            const inputs = document.querySelectorAll('#form-data-inputs .form-data-row');
            let lines = [];
            
            inputs.forEach(row => {
                const key = row.querySelector('.form-key').value;
                const valueInput = row.querySelector('.form-value');
                
                if (key) {
                    if (valueInput.type === 'file') {
                        lines.push(`${key}: (Binary File)`);
                    } else {
                        lines.push(`${key}: ${valueInput.value}`);
                    }
                }
            });
            content = lines.join('\n');
        }

        if (content) {
            copyToClipboard(content, btn);
        }
    }

    function copyResponseBody(btn) {
        let content = '';
        if (aceResponseViewer) {
            content = aceResponseViewer.getValue();
            // If it matches the placeholder, don't copy
            if (content === 'Click "Send Request" to see response...') {
                content = '';
            }
        }
        
        if (content) {
            copyToClipboard(content, btn);
        }
    }

    function addFormDataField(key = '', value = '') {
        const container = document.getElementById('form-data-inputs');
        const id = Math.random().toString(36).substr(2, 9);
        const isFile = typeof value === 'string' && value.includes('(File Object)');
        
        const div = document.createElement('div');
        div.className = 'flex items-start gap-2 form-data-row group/field';
        div.innerHTML = `
            <div class="flex-1 grid grid-cols-[1fr_auto_2fr] gap-2">
                <input type="text" placeholder="Key" value="${key}" class="form-key bg-black/40 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-indigo-500/50 placeholder:text-slate-600">
                
                <select onchange="toggleRowType(this)" class="form-type bg-black/40 border border-white/10 rounded-lg px-2 py-2 text-xs text-slate-400 focus:outline-none focus:border-indigo-500/50 cursor-pointer">
                    <option value="text" ${!isFile ? 'selected' : ''}>Text</option>
                    <option value="file" ${isFile ? 'selected' : ''}>File</option>
                </select>

                <div class="form-value-container w-full">
                    ${isFile 
                        ? `<input type="file" class="form-value file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-[10px] file:bg-indigo-500/10 file:text-indigo-400 hover:file:bg-indigo-500/20 text-xs text-slate-300 w-full">`
                        : `<input type="text" placeholder="Value" value="${value}" class="form-value bg-black/40 border border-white/10 rounded-lg px-3 py-2 text-xs text-slate-300 focus:outline-none focus:border-indigo-500/50 placeholder:text-slate-600 w-full">`
                    }
                </div>
            </div>
            <button onclick="this.closest('.flex').remove()" class="p-2 rounded-lg text-slate-500 hover:text-red-400 hover:bg-red-500/10 transition-all opacity-0 group-hover/field:opacity-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
            </button>
        `;
        container.appendChild(div);
    }

    function toggleRowType(select) {
        const type = select.value;
        const container = select.nextElementSibling; // form-value-container
        
        if (type === 'text') {
            container.innerHTML = `<input type="text" placeholder="Value" class="form-value bg-black/40 border border-white/10 rounded-lg px-3 py-2 text-xs text-slate-300 focus:outline-none focus:border-indigo-500/50 placeholder:text-slate-600 w-full">`;
        } else {
            container.innerHTML = `<input type="file" class="form-value file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-[10px] file:bg-indigo-500/10 file:text-indigo-400 hover:file:bg-indigo-500/20 text-xs text-slate-300 w-full">`;
        }
    }

    function showCodeExampleFromModal() {
        const method = document.getElementById('doc-method').innerText;
        const url = document.getElementById('doc-endpoint').innerText;
        const body = document.getElementById('doc-body').innerText;
        try {
             showCodeExample(method, url, JSON.parse(body));
        } catch(e) {
             showCodeExample(method, url, body);
        }
    }

    function closeCodeModal() {
        document.getElementById('code-modal').classList.add('hidden');
    }
    
    async function executeTryRequest() {
        const btn = document.getElementById('btn-execute');
        const statusBadge = document.getElementById('try-status');
        const originalBtnText = btn.innerHTML;
        
        // UI Loading State
        btn.disabled = true;
        btn.innerHTML = `<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Sending...`;
        
        try {
            const method = currentCodeData.method;
            const url = currentCodeData.url;
            
            // Determine token based on URL path or context
            let token = getTokenForUrl(url);

            const headers = {
                 'Accept': 'application/json',
                 'Authorization': 'Bearer ' + token
            };

            let bodyPayload;

            if (method !== 'GET' && method !== 'HEAD') {
                if (currentBodyType === 'json') {
                    // Get Value from Ace
                    const bodyContent = aceRequestEditor ? aceRequestEditor.getValue() : '';
                    
                    if (bodyContent && bodyContent.trim() !== '') {
                        try {
                            JSON.parse(bodyContent); // Validate
                            bodyPayload = bodyContent;
                            headers['Content-Type'] = 'application/json';
                        } catch (e) {
                            throw new Error("Invalid JSON in request body");
                        }
                    }
                } else {
                    // Form Data Construction
                    const formData = new FormData();
                    const inputs = document.querySelectorAll('#form-data-inputs .form-data-row');
                    
                    inputs.forEach(row => {
                        const key = row.querySelector('.form-key').value;
                        const valueInput = row.querySelector('.form-value');
                        
                        if (key) {
                            if (valueInput.type === 'file') {
                                if (valueInput.files[0]) {
                                    formData.append(key, valueInput.files[0]);
                                }
                            } else {
                                formData.append(key, valueInput.value);
                            }
                        }
                    });
                    
                    // Do NOT set Content-Type header manually for FormData, let browser set boundary
                    bodyPayload = formData;
                }
            }
            
            const response = await fetch(url, {
                method: method,
                headers: headers,
                body: bodyPayload
            });

            // Handling 204 No Content or responses without body
            let data = {};
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.indexOf("application/json") !== -1) {
                data = await response.json();
            } else {
                data = { message: "Request successful, no JSON content returned." };
            }
            
            // Update Status Badge
            statusBadge.innerText = `${response.status} ${response.statusText}`;
            if (response.ok) {
                statusBadge.className = "px-4 py-1.5 rounded-xl text-[10px] font-black uppercase bg-emerald-500/20 text-emerald-400 border border-emerald-500/20";
            } else {
                statusBadge.className = "px-4 py-1.5 rounded-xl text-[10px] font-black uppercase bg-red-500/20 text-red-400 border border-red-500/20";
            }
            
            // Update Response Display
            if (aceResponseViewer) {
                aceResponseViewer.setValue(JSON.stringify(data, null, 4), -1);
            }
            
        } catch (error) {
            statusBadge.innerText = "ERROR";
            statusBadge.className = "px-2 py-1 rounded-lg text-[10px] font-bold uppercase bg-red-500/20 text-red-400";
            if (aceResponseViewer) {
                 aceResponseViewer.setValue(JSON.stringify({ error: error.message }, null, 4), -1);
            }
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalBtnText;
        }
    }

    function switchLanguage(lang) {
        // Update Buttons
        document.querySelectorAll('.lang-btn').forEach(btn => {
            btn.className = 'lang-btn px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-white transition-all';
            if (btn.getAttribute('data-lang') === lang) {
                btn.classList.remove('text-slate-500');
                btn.classList.add('text-white', 'bg-indigo-500');
            }
        });

        // Toggle Sections
        const codeSection = document.getElementById('code-section');
        const usageSection = document.getElementById('usage-section');
        const trySection = document.getElementById('try-section');
        
        if (lang === 'try') {
            codeSection.classList.add('hidden');
            usageSection.classList.add('hidden');
            trySection.classList.remove('hidden');
            
            // Highlight the pre-filled JSON if needed
            // (Textareas don't use Prism, but we are good)
            return;
        }
        
        // Show Standard Code Sections
        trySection.classList.add('hidden');
        codeSection.classList.remove('hidden');
        // usageSection visibility is controlled below logic

        const { method, url, body } = currentCodeData;
        let code = '';
        let prismLang = 'javascript';

        // Update Language Classes
        const codeElement = document.getElementById('code-display');
        const preElement = document.getElementById('code-pre');
        
        if (lang === 'php') prismLang = 'php';
        else if (lang === 'curl') prismLang = 'bash';
        else if (lang === 'react' || lang === 'rtk') prismLang = 'jsx';
        else if (lang === 'docs') prismLang = 'plaintext';

        preElement.className = `language-${prismLang}`;
        codeElement.className = `language-${prismLang}`;

        const usageDisplay = document.getElementById('usage-display');
        const usagePre = document.getElementById('usage-pre');
        usageSection.classList.add('hidden');
        let usageCode = '';
        
        const headers = {
            'Authorization': 'Bearer YOUR_TOKEN',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        };

        const bodyStr = body ? (typeof body === 'string' ? body : JSON.stringify(body, null, 2)) : '';
        const bodyCompact = body ? (typeof body === 'string' ? body : JSON.stringify(body)) : '';

        if (lang === 'docs') {
            const endpointName = url.split('/').pop().replace(/-/g, ' ');
            const capitalizedEndpoint = endpointName.charAt(0).toUpperCase() + endpointName.slice(1);
            
            code = `INTEGRATION GUIDE: ${method} ${capitalizedEndpoint}

1. OVERVIEW
   This endpoint allows you to ${method === 'GET' ? 'fetch' : (method === 'POST' ? 'create' : 'update')} ${endpointName} data programmatically.

2. AUTHENTICATION
   Requirement: Bearer Token
   Header: 'Authorization: Bearer YOUR_TOKEN'
   
   * Obtain your token from the Admin > Security settings.
   * Ensure your domain is whitelisted in "Allowed Origins".

3. REQUEST DETAILS
   Endpoint: ${url}
   Method: ${method}
   Headers: 
     - Accept: application/json
     - Content-Type: application/json
${body ? `\n   Request Body (JSON):\n${bodyStr}\n` : ''}
4. INTEGRATION STEPS
   a. Set up your HTTP client with the base URL and authentication headers.
   b. ${method !== 'GET' ? 'Construct the JSON payload matching the schema above.' : 'Send a GET request to the specified endpoint.'}
   c. Parse the JSON response. A success usually returns a 200 or 201 status code.
   d. Handle 401 (Unauthorized) or 403 (Forbidden) by checking your token and CORS settings.

5. SAMPLE RESPONSE HANDLING
   Success: { "status": "success", "data": { ... } }
   Error: { "status": "error", "message": "Reason for failure" }`;
        } else if (lang === 'js') {
            const fetchOptions = {
                method: method,
                headers: headers
            };
            if (body) fetchOptions.body = 'BODY_PLACEHOLDER';
            
            code = `fetch('${url}', ${JSON.stringify(fetchOptions, null, 2)})`
                .replace('"BODY_PLACEHOLDER"', bodyStr) + 
                `\n.then(response => response.json())\n.then(data => console.log(data))\n.catch(error => console.error('Error:', error));`;
        } else if (lang === 'rtk') {
            const endpointBase = url.split('/').pop().replace(/-/g, '');
            const endpointName = method.toLowerCase() + endpointBase.charAt(0).toUpperCase() + endpointBase.slice(1);
            const isMutation = method !== 'GET';
            const relativeUrl = url.replace(window.location.origin + '/api/', '');
            
            code = `// 1. API Slice Definition (@reduxjs/toolkit/query/react)
import { createApi, fetchBaseQuery } from '@reduxjs/toolkit/query/react';

export const api = createApi({
  reducerPath: 'api',
  baseQuery: fetchBaseQuery({ 
    baseUrl: '${window.location.origin}/api/',
    prepareHeaders: (headers) => {
      headers.set('Auth' + 'orization', 'Bearer YOUR_TOKEN');
      headers.set('Accept', 'application/json');
      return headers;
    },
  }),
  endpoints: (builder) => ({
    ${endpointName}: builder.${isMutation ? 'mutation' : 'query'}({
      query: (${body ? 'data' : ''}) => ({
        url: '${relativeUrl}',
        method: '${method}',
        ${body ? 'body: data' : ''}
      }),
    }),
  }),
});

export const { use${endpointName.charAt(0).toUpperCase() + endpointName.slice(1)}${isMutation ? 'Mutation' : 'Query'} } = api;`;

            usageCode = `// 2. Component Usage Example
function MyComponent() {
  // Hook usage
  const [${endpointName}, { isLoading, error }] = use${endpointName.charAt(0).toUpperCase() + endpointName.slice(1)}${isMutation ? 'Mutation' : 'Query'}();

  const handleExecute = async () => {
    const payload = ${bodyStr || '{}'};
    try {
      const response = await ${endpointName}(payload).unwrap();
      console.log('Success:', response);
    } catch (err) {
      console.error('Failed:', err);
    }
  };

  return (
    <button onClick={handleExecute} disabled={isLoading}>
      {isLoading ? 'Processing...' : 'Run ${method}'}
    </button>
  );
}`;
            usageSection.classList.remove('hidden');
        } else if (lang === 'react') {
            const isGet = method === 'GET';
            const endpointName = url.split('/').pop().replace(/-/g, ' ');
            const componentName = endpointName.charAt(0).toUpperCase() + endpointName.slice(1).replace(/\s+/g, '') + 'Manager';
            const stateName = endpointName.replace(/\s+/g, '');
            
            usageCode = `// Implementation Tip:
// You can directly drop this component into your codebase.
// Make sure to replace 'YOUR_TOKEN' with your actual token.`;

            code = `// React / Next.js Component Example
import React, { useState, useEffect } from 'react';

const ${componentName} = () => {
  const [${stateName}, set${stateName.charAt(0).toUpperCase() + stateName.slice(1)}] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const containerStyle = { padding: '20px', border: '1px solid #ccc', borderRadius: '12px' };
  const errorStyle = { color: 'red' };
  const preStyle = { background: '#f4f4f4', padding: '10px' };
  const buttonStyle = { padding: '10px 20px', background: '#4f46e5', color: 'white', border: 'none', borderRadius: '8px' };

  const handleRequest = async (${body ? 'payload' : ''}) => {
    setLoading(true);
    setError(null);
    try {
      const response = await fetch('${url}', {
        method: '${method}',
        headers: {
          ['Auth' + 'orization']: 'Bearer YOUR_TOKEN',
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        ${body ? 'body: JSON.stringify(payload)' : ''}
      });
      const result = await response.json();
      if (!response.ok) throw new Error(result.message || 'API Error');
      set${stateName.charAt(0).toUpperCase() + stateName.slice(1)}(isGet ? (result.data || result) : result);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  ${isGet ? `useEffect(() => {
    handleRequest();
  }, []); // Initial load for GET` : ''}

  return (
    <div style={containerStyle}>
      <h2>${method} ${endpointName.toUpperCase()}</h2>
      
      {loading && <p>Loading...</p>}
      {error && <p style={errorStyle}>Error: {error}</p>}
      
      {${stateName} && (
        <pre style={preStyle}>
          {JSON.stringify(${stateName}, null, 2)}
        </pre>
      )}

      ${!isGet ? `
      <button 
        onClick={() => handleRequest(${bodyStr || '{}'})}
        style={buttonStyle}
      >
        Run Action
      </button>` : ''}
    </div>
  );
};

export default ${componentName};`;

            usageSection.classList.remove('hidden');
        } else if (lang === 'php') {
            let phpBody = '';
            if (body) {
                const escapedBody = bodyCompact.replace(/'/g, "\\'");
                phpBody = `\ncurl_setopt($ch, CURLOPT_POSTFIELDS, '${escapedBody}');`;
            }
            
            code = '<' + '\x3f' + `php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "${url}");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "${method}");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer YOUR_TOKEN',
    'Accept: application/json',
    'Content-Type: application/json'
]);${phpBody}

$response = curl_exec($ch);
curl_close($ch);
echo $response;`;
        } else if (lang === 'curl') {
            const dataRaw = body ? ` \\\n--data-raw '${bodyCompact}'` : '';
            code = `curl --location --request ${method} '${url}' \\
--header 'Authorization: Bearer YOUR_TOKEN' \\
--header 'Accept: application/json' \\
--header 'Content-Type: application/json'${dataRaw}`;
        }

        // Apply Usage Example if active
        if (!usageSection.classList.contains('hidden')) {
            usagePre.className = `language-${prismLang}`;
            usageDisplay.className = `language-${prismLang}`;
            usageDisplay.textContent = usageCode;
            if (window.Prism) Prism.highlightElement(usageDisplay);
        }

        const display = document.getElementById('code-display');
        display.textContent = code;
        if (window.Prism) {
            Prism.highlightElement(display);
        }
    }

    function copyCodeContent(btn, elementId = 'code-display') {
        const content = document.getElementById(elementId).innerText;
        copyToClipboard(content, btn);
    }

    function copyToClipboard(text, btn = null) {
        const copyAction = () => {
            showToast('Copied to clipboard!');
            if (btn) {
                const copyIcon = btn.querySelector('.icon-copy');
                const checkIcon = btn.querySelector('.icon-check');
                if (copyIcon && checkIcon) {
                    copyIcon.classList.add('hidden');
                    checkIcon.classList.remove('hidden');
                    setTimeout(() => {
                        copyIcon.classList.remove('hidden');
                        checkIcon.classList.add('hidden');
                    }, 2000);
                }
            }
        };

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(copyAction).catch(err => {
                console.error('Clipboard API failed, trying fallback:', err);
                fallbackCopyTextToClipboard(text, copyAction);
            });
        } else {
            fallbackCopyTextToClipboard(text, copyAction);
        }
    }

    function fallbackCopyTextToClipboard(text, callback) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.position = "fixed";
        textArea.style.left = "-9999px";
        textArea.style.top = "0";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            const successful = document.execCommand('copy');
            if (successful) callback();
        } catch (err) {
            console.error('Fallback copy failed:', err);
            showToast('Failed to copy', 'error');
        }
        document.body.removeChild(textArea);
    }

    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        const bgColor = type === 'success' ? 'bg-emerald-500' : 'bg-red-500';
        toast.className = `fixed bottom-8 right-8 px-6 py-3 rounded-xl ${bgColor} text-white font-bold shadow-2xl z-[100] transition-all transform translate-y-20 opacity-0`;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.remove('translate-y-20', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
        }, 10);
        
        setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0');
            toast.classList.remove('translate-y-0', 'opacity-100');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }
</script>

<!-- Prism Core -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
<!-- Languages -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markup-templating.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-jsx.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-bash.min.js"></script>
<!-- Ace Editor -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/ace.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/mode-json.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/mode-javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/mode-php.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/mode-sh.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/theme-tomorrow_night.min.js"></script>
