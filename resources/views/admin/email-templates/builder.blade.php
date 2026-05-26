@extends('admin.layout')

@section('title', isset($emailTemplate) ? 'Edit Template' : 'Create Template')

@section('content')
<div class="flex flex-col gap-4">
    <!-- Top Professional Bar -->
    <div class="glass p-4 rounded-2xl border border-white/10 flex flex-wrap items-center justify-between gap-4 font-outfit">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.email-templates.index') }}" class="p-2 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white rounded-xl transition-all border border-white/10" title="Back to List">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div class="h-8 w-px bg-white/10"></div>
            <div>
                <input type="text" id="template-name" value="{{ $emailTemplate->name ?? '' }}" class="bg-transparent border-none text-white font-bold text-lg focus:ring-0 p-0 w-48" placeholder="Template Name">
                <input type="text" id="template-subject" value="{{ $emailTemplate->subject ?? '' }}" class="block bg-transparent border-none text-slate-400 text-xs focus:ring-0 p-0 w-64" placeholder="Email Subject line...">
            </div>
        </div>

        <div class="flex items-center gap-3 bg-white/5 p-1 rounded-xl border border-white/10">
            <button onclick="setDevice('desktop')" id="btn-desktop" class="p-2 rounded-lg bg-indigo-600 text-white transition-all shadow-lg" title="Desktop View">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 21h6l-.75-4M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </button>
            <button onclick="setDevice('mobile')" id="btn-mobile" class="p-2 rounded-lg text-slate-400 hover:text-white transition-all" title="Mobile View">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </button>
        </div>

        <div class="flex items-center gap-4">
            <select id="template-select" onchange="loadBuiltInTemplate(this.value)" class="bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-xs text-slate-300 focus:outline-none focus:border-indigo-500 transition-all">
                <option value="">Built-in Styles</option>
                <option value="welcome">Welcome Email</option>
                <option value="newsletter">Newsletter</option>
                <option value="promotion">Promotion</option>
                <option value="product_launch">Launch</option>
            </select>
            <div class="h-8 w-px bg-white/10"></div>
            <button onclick="sendTestEmail()" class="flex items-center gap-2 px-4 py-2 bg-white/5 hover:bg-white/10 text-white font-bold rounded-xl transition-all border border-white/10 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Test
            </button>
            <button onclick="saveTemplate()" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-indigo-500/20 text-sm">
                Save Design
            </button>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Builder Area -->
        <div class="flex-1 glass p-2 rounded-2xl border border-white/5">
            <div id="unlayer-editor" style="height: 85vh; border-radius: 1rem; overflow: hidden;"></div>
        </div>

    <!-- Variable Guide Sidebar -->
    <div class="w-full lg:w-72 space-y-6">
        <div class="glass p-6 rounded-2xl border border-indigo-500/20">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                Dynamic Variables
            </h3>
            <p class="text-xs text-slate-400 mb-4">You can use these tags anywhere in your template. They will be replaced with real data when sending.</p>
            
            <div class="space-y-4">
                <div class="p-3 bg-white/5 rounded-xl border border-white/5">
                    <code class="text-indigo-400 font-bold font-mono text-sm leading-none">@{{name}}</code>
                    <p class="text-[11px] text-slate-500 mt-1">Recipient's Full Name</p>
                </div>
                <div class="p-3 bg-white/5 rounded-xl border border-white/5">
                    <code class="text-indigo-400 font-bold font-mono text-sm leading-none">@{{email}}</code>
                    <p class="text-[11px] text-slate-500 mt-1">Recipient's Email Address</p>
                </div>
                <div class="p-3 bg-white/5 rounded-xl border border-white/5">
                    <code class="text-indigo-400 font-bold font-mono text-sm leading-none">@{{date}}</code>
                    <p class="text-[11px] text-slate-500 mt-1">Current Date</p>
                </div>
                <div class="p-3 bg-white/5 rounded-xl border border-white/5">
                    <code class="text-indigo-400 font-bold font-mono text-sm leading-none">@{{company}}</code>
                    <p class="text-[11px] text-slate-500 mt-1">Your Company Name</p>
                </div>
                <div class="p-3 bg-white/5 rounded-xl border border-white/5">
                    <code class="text-indigo-400 font-bold font-mono text-sm leading-none">@{{your key name which you sent using array}}</code>
                    <p class="text-[11px] text-slate-500 mt-1">\Mail::to($recipient['email'])->send(new \App\Mail\DynamicEmail($this->subject, $this->content, ['name' => $recipient['name']]));</p>
                </div>
            </div>

            <div class="mt-8 p-4 bg-indigo-500/10 rounded-xl border border-indigo-500/20">
                <p class="text-[10px] text-indigo-300 font-medium italic">Tip: Use the <strong>"Content"</strong> tab for basic elements and the <strong>"Blocks"</strong> tab (grid icon) for professional sections like Hero, Pricing, and Testimonials!</p>
            </div>
        </div>
    </div>
</div>

<!-- Unlayer Editor Script -->
<script src="https://editor.unlayer.com/embed.js"></script>

<script>
    unlayer.init({
        id: 'unlayer-editor',
        displayMode: 'email',
        appearance: {
            theme: 'dark',
            panels: {
                tools: {
                    dock: 'left'
                }
            }
        },
        tools: {
            'text': { enabled: true },
            'image': { enabled: true },
            'button': { enabled: true },
            'divider': { enabled: true },
            'social': { enabled: true },
            'html': { enabled: true },
            'video': { enabled: true },
            'menu': { enabled: true },
            'timer': { enabled: true },
            'sticker': { enabled: true },
        },
        features: {
            stockPhotos: true,
        },
        customBlocks: [
            {
                id: 'hero-section',
                name: 'Hero Section',
                description: 'Stylish hero banner with button',
                category: 'Layout',
                content: {
                    body: {
                        rows: [{
                            cells: [1],
                            columns: [{
                                contents: [
                                    {
                                        type: 'heading',
                                        values: {
                                            text: 'Welcome to ZilMoney',
                                            color: '#ffffff',
                                            backgroundColor: '#6366f1',
                                            textAlign: 'center',
                                            padding: '40px 20px 10px 20px'
                                        }
                                    },
                                    {
                                        type: 'text',
                                        values: {
                                            text: '<p style="text-align:center; color:#ffffff; background-color:#6366f1; margin:0; padding:0 20px 20px 20px;">The best platform for your business needs.</p>',
                                            backgroundColor: '#6366f1'
                                        }
                                    },
                                    {
                                        type: 'button',
                                        values: {
                                            text: 'Get Started',
                                            backgroundColor: '#ffffff',
                                            color: '#6366f1',
                                            textAlign: 'center',
                                            padding: '10px 20px 40px 20px'
                                        }
                                    }
                                ]
                            }]
                        }]
                    }
                },
                icon: 'fa-star'
            },
            {
                id: 'three-features',
                name: '3-Column Features',
                description: 'List your features in 3 columns',
                category: 'Marketing',
                content: {
                    body: {
                        rows: [{
                            cells: [1, 1, 1],
                            columns: [
                                { contents: [{ type: 'text', values: { text: '<strong>Feature 1</strong><p>Details about feature 1.</p>' } }] },
                                { contents: [{ type: 'text', values: { text: '<strong>Feature 2</strong><p>Details about feature 2.</p>' } }] },
                                { contents: [{ type: 'text', values: { text: '<strong>Feature 3</strong><p>Details about feature 3.</p>' } }] }
                            ]
                        }]
                    }
                },
                icon: 'fa-list'
            },
            {
                id: 'pricing-table',
                name: 'Pricing Table',
                description: 'Professional 3-column pricing',
                category: 'Marketing',
                content: {
                    body: {
                        rows: [{
                            cells: [1, 1, 1],
                            columns: [
                                {
                                    contents: [
                                        { type: 'text', values: { text: '<h3 style="text-align:center;">Basic</h3><p style="text-align:center; font-size:24px;">$9</p>', padding: '10px' } },
                                        { type: 'button', values: { text: 'Buy Now', backgroundColor: '#64748b', textAlign: 'center' } }
                                    ]
                                },
                                {
                                    contents: [
                                        { type: 'text', values: { text: '<h3 style="text-align:center; color:#6366f1;">Pro</h3><p style="text-align:center; font-size:24px;">$29</p>', padding: '10px' } },
                                        { type: 'button', values: { text: 'Buy Now', backgroundColor: '#6366f1', textAlign: 'center' } }
                                    ]
                                },
                                {
                                    contents: [
                                        { type: 'text', values: { text: '<h3 style="text-align:center;">Enterprise</h3><p style="text-align:center; font-size:24px;">$99</p>', padding: '10px' } },
                                        { type: 'button', values: { text: 'Buy Now', backgroundColor: '#64748b', textAlign: 'center' } }
                                    ]
                                }
                            ]
                        }]
                    }
                },
                icon: 'fa-table'
            },
            {
                id: 'testimonial-block',
                name: 'Testimonial',
                description: 'Customer review section',
                category: 'Social Proof',
                content: {
                    body: {
                        rows: [{
                            cells: [1],
                            columns: [{
                                contents: [
                                    {
                                        type: 'text',
                                        values: {
                                            text: '<div style="background:#f8fafc; padding:20px; border-radius:10px; font-style:italic; text-align:center;">"ZilMoney has transformed our team\'s productivity. Highly recommended!"<br><br><strong>- Jane Doe</strong></div>'
                                        }
                                    }
                                ]
                            }]
                        }]
                    }
                },
                icon: 'fa-quote-left'
            },
            {
                id: 'company-signature',
                name: 'Company Signature',
                description: 'Professional signature block',
                category: 'Personalization',
                content: {
                    body: {
                        rows: [{
                            cells: [1],
                            columns: [{
                                contents: [
                                    {
                                        type: 'text',
                                        values: {
                                            text: '<p style="margin:0; font-weight:bold; color:#1e293b;">@{{company}} Team</p><p style="margin:0; color:#64748b; font-size:12px;">Providing smart financial solutions</p>'
                                        }
                                    }
                                ]
                            }]
                        }]
                    }
                },
                icon: 'fa-signature'
            },
            {
                id: 'promo-banner',
                name: 'Promo Banner',
                description: 'Stylish promotion banner',
                category: 'Marketing',
                content: {
                    body: {
                        rows: [{
                            cells: [1],
                            columns: [{
                                contents: [
                                    {
                                        type: 'heading',
                                        values: {
                                            text: 'Special Sale! 50% OFF',
                                            color: '#ffffff',
                                            backgroundColor: '#6366f1',
                                            textAlign: 'center',
                                            padding: '20px'
                                        }
                                    }
                                ]
                            }]
                        }]
                    }
                },
                icon: 'fa-tags'
            }
        ]
    });

    const builtInTemplates = {
        welcome: {
            body: {
                rows: [
                    {
                        cells: [1],
                        columns: [{
                            contents: [
                                { type: 'image', values: { src: { url: 'https://via.placeholder.com/150x50?text=LOGO' }, textAlign: 'center', padding: '20px' } },
                                { type: 'heading', values: { text: 'Welcome to the Family! 🚀', textAlign: 'center', color: '#6366f1' } },
                                { type: 'text', values: { text: '<p>Hi @{{name}},</p><p>We\'re thrilled to have you with us over at @{{company}}! You\'ve just taken the first step towards smarter financial management.</p>', textAlign: 'center' } },
                                { type: 'button', values: { text: 'Get Started Now', backgroundColor: '#6366f1', color: '#ffffff', textAlign: 'center' } },
                                { type: 'divider', values: { padding: '20px' } },
                                { type: 'social', values: { textAlign: 'center' } }
                            ]
                        }]
                    }
                ]
            }
        },
        newsletter: {
            body: {
                rows: [
                    {
                        cells: [1],
                        columns: [{ contents: [{ type: 'heading', values: { text: 'Monthly News & Updates 📰', textAlign: 'center', backgroundColor: '#f8fafc', padding: '30px' } }] }]
                    },
                    {
                        cells: [1],
                        columns: [{
                            contents: [
                                { type: 'text', values: { text: '<h3>Latest from @{{company}}</h3><p>Here is what we have been up to this month and what you might have missed.</p>' } },
                                { type: 'image', values: { src: { url: 'https://via.placeholder.com/600x300?text=News+Feature+Image' } } }
                            ]
                        }]
                    },
                    {
                        cells: [1, 1],
                        columns: [
                            { contents: [{ type: 'text', values: { text: '<strong>Topic A</strong><p>Brief summary of top feature A.</p>' } }] },
                            { contents: [{ type: 'text', values: { text: '<strong>Topic B</strong><p>Brief summary of top feature B.</p>' } }] }
                        ]
                    }
                ]
            }
        },
        promotion: {
            body: {
                rows: [
                    {
                        cells: [1],
                        columns: [{
                            contents: [
                                { type: 'timer', values: { timerColor: '#6366f1', textAlign: 'center', padding: '20px' } },
                                { type: 'heading', values: { text: 'Flash Sale: 50% OFF! 🎁', color: '#ffffff', backgroundColor: '#e11d48', textAlign: 'center', padding: '40px' } },
                                { type: 'text', values: { text: '<p style="text-align:center;">Don\'t miss out! Use code <strong>SAVE50</strong> at checkout.</p>' } },
                                { type: 'button', values: { text: 'Shop the Sale', backgroundColor: '#e11d48', color: '#ffffff', textAlign: 'center' } }
                            ]
                        }]
                    }
                ]
            }
        },
        product_launch: {
            body: {
                rows: [
                    {
                        cells: [1],
                        columns: [{ contents: [{ type: 'image', values: { src: { url: 'https://via.placeholder.com/600x400?text=New+Product+Launch' }, textAlign: 'center' } }] }]
                    },
                    {
                        cells: [1],
                        columns: [{
                            contents: [
                                { type: 'heading', values: { text: 'Meet the New ZilMoney! ✨', textAlign: 'center' } },
                                { type: 'text', values: { text: '<p style="text-align:center;">Our biggest update yet is finally here. Designed to help you scale faster.</p>', textAlign: 'center' } }
                            ]
                        }]
                    },
                    {
                        cells: [1, 1, 1],
                        columns: [
                            { contents: [{ type: 'text', values: { text: '<strong>Fast</strong><p>Speed like never before.</p>', textAlign: 'center' } }] },
                            { contents: [{ type: 'text', values: { text: '<strong>Secure</strong><p>Encryption at every level.</p>', textAlign: 'center' } }] },
                            { contents: [{ type: 'text', values: { text: '<strong>Easy</strong><p>Just one click away.</p>', textAlign: 'center' } }] }
                        ]
                    },
                    {
                        cells: [1],
                        columns: [{ contents: [{ type: 'button', values: { text: 'Explore New Features', backgroundColor: '#6366f1', textAlign: 'center' } }] }]
                    }
                ]
            }
        },
        event: {
            body: {
                rows: [
                    {
                        cells: [1],
                        columns: [{
                            contents: [
                                { type: 'heading', values: { text: 'YOU\'RE INVITED! ✉️', color: '#1e293b', textAlign: 'center', padding: '30px' } },
                                { type: 'text', values: { text: '<div style="text-align:center; padding:20px; background:#f1f5f9; border-radius:10px;"><h3>Annual FinTech Summit</h3><p>📅 Feb 28th, 2026 | 📍 Live Webinar</p></div>', padding: '10px' } },
                                { type: 'text', values: { text: '<p>Join us for an exclusive look at the future of finance. We\'ll be discussing AI in banking and more.</p>' } },
                                { type: 'button', values: { text: 'RSVP Now', backgroundColor: '#0f172a', textAlign: 'center' } }
                            ]
                        }]
                    }
                ]
            }
        },
        feedback: {
            body: {
                rows: [
                    {
                        cells: [1],
                        columns: [{
                            contents: [
                                { type: 'heading', values: { text: 'How are we doing? ⭐', textAlign: 'center' } },
                                { type: 'text', values: { text: '<p>Hi @{{name}}, we value your feedback. Tell us what you think about our latest update.</p>', textAlign: 'center' } },
                                { type: 'button', values: { text: 'Take 2-Min Survey', backgroundColor: '#6366f1', textAlign: 'center' } }
                            ]
                        }]
                    }
                ]
            }
        },
        holiday: {
            body: {
                rows: [
                    {
                        cells: [1],
                        columns: [{
                            contents: [
                                { type: 'heading', values: { text: 'Happy Holidays! 🎄', color: '#ffffff', backgroundColor: '#15803d', textAlign: 'center', padding: '50px' } },
                                { type: 'text', values: { text: '<p style="text-align:center;">Season\'s greetings from @{{company}}! Enjoy our holiday gift to you.</p>' } },
                                { type: 'heading', values: { text: '30% OFF EVERYTHING', textAlign: 'center', color: '#b91c1c' } },
                                { type: 'button', values: { text: 'Claim Your Gift', backgroundColor: '#b91c1c', textAlign: 'center' } }
                            ]
                        }]
                    }
                ]
            }
        }
    };

    function loadBuiltInTemplate(key) {
        if (!key || !builtInTemplates[key]) return;
        
        if (confirm('Loading a new template will overwrite your current design. Continue?')) {
            unlayer.loadDesign(builtInTemplates[key]);
            
            // Auto-update subject for convenience
            const subjects = { 
                welcome: "Welcome to @{{company}}!", 
                newsletter: "Your Monthly Update from @{{company}}", 
                promotion: "Flash Sale: Grab 50% OFF!",
                product_launch: "Big News: Meeting the New ZilMoney! 🚀",
                event: "You're Invited: Annual FinTech Summit",
                feedback: "Got 2 minutes? We'd love your feedback",
                holiday: "Happy Holidays from @{{company}}! 🎁"
            };
            if (subjects[key]) document.getElementById('template-subject').value = subjects[key];
        }
    }

    // Load existing design if available
    @if(isset($emailTemplate) && $emailTemplate->content_json)
        unlayer.loadDesign({!! $emailTemplate->content_json !!});
    @else
        // Set default design
        unlayer.loadDesign({
            "body": {
                "rows": [{
                    "cells": [1],
                    "columns": [{
                        "contents": [
                            {
                                "type": "heading",
                                "values": {
                                    "headingType": "h1",
                                    "text": "Hello @{{name}},",
                                    "color": "#6366f1",
                                    "textAlign": "center"
                                }
                            },
                            {
                                "type": "text",
                                "values": {
                                    "text": "Welcome to ZilMoney! Start designing your professional email by dragging elements from the right panel.",
                                    "textAlign": "center"
                                }
                            }
                        ]
                    }]
                }]
            }
        });
    @endif

    function setDevice(device) {
        unlayer.setDevice(device);
        
        const btnDesktop = document.getElementById('btn-desktop');
        const btnMobile = document.getElementById('btn-mobile');
        
        if (device === 'desktop') {
            btnDesktop.classList.add('bg-indigo-600', 'text-white', 'shadow-lg');
            btnDesktop.classList.remove('text-slate-400');
            btnMobile.classList.remove('bg-indigo-600', 'text-white', 'shadow-lg');
            btnMobile.classList.add('text-slate-400');
        } else {
            btnMobile.classList.add('bg-indigo-600', 'text-white', 'shadow-lg');
            btnMobile.classList.remove('text-slate-400');
            btnDesktop.classList.remove('bg-indigo-600', 'text-white', 'shadow-lg');
            btnDesktop.classList.add('text-slate-400');
        }
    }

    async function sendTestEmail() {
        const email = prompt("Enter email address to send test to:", "{{ auth()->user()->email ?? '' }}");
        if (!email) return;

        const subject = document.getElementById('template-subject').value || "Test Email";

        unlayer.exportHtml(async function(data) {
            const content_html = data.html;

            const response = await fetch('{{ route('admin.email-sender.test') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    email,
                    subject,
                    content_html,
                    _token: '{{ csrf_token() }}'
                })
            });

            const result = await response.json();
            alert(result.message);
        });
    }

    async function saveTemplate() {
        const name = document.getElementById('template-name').value;
        const subject = document.getElementById('template-subject').value;

        if(!name || !subject) {
            alert('Please fill Name and Subject');
            return;
        }

        // Export design from Unlayer
        unlayer.exportHtml(async function(data) {
            const content_html = data.html;
            const content_json = JSON.stringify(data.design);

            const formData = {
                name,
                subject,
                content_html,
                content_json,
                _token: '{{ csrf_token() }}'
            };

            const url = '{{ isset($emailTemplate) ? '/api/admin/email-templates/'.$emailTemplate->id : '/api/admin/email-templates' }}';
            const method = '{{ isset($emailTemplate) ? 'PUT' : 'POST' }}';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();
                if(!result.isError && response.ok) {
                    alert(result.Message || 'Template saved successfully');
                    window.location.href = "{{ route('admin.email-templates.index') }}";
                } else {
                    alert('Error: ' + (result.Message || JSON.stringify(result.errors || result.error)));
                }
            } catch (error) {
                console.error('Error saving template:', error);
                alert('Failed to save template.');
            }
        });
    }
</script>
@endsection
