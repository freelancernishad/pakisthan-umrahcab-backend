<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Google Fonts: Inter & Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Outfit', sans-serif; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        /* Custom Radio */
        .custom-radio:checked + div { border-color: #3b82f6; background-color: #eff6ff; }
        .custom-radio:checked + div .radio-circle { border-color: #3b82f6; background-color: #3b82f6; }
        .custom-radio:checked + div .radio-dot { opacity: 1; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-100 via-blue-50 to-indigo-50 min-h-screen flex items-center justify-center p-4">

    <div id="main-container" class="w-full max-w-lg animate-fade-in transition-all duration-500">
        <!-- Main Card -->
        <div class="glass-panel rounded-2xl shadow-2xl overflow-hidden relative">
            <!-- Decorative Header Gradient -->
            <div class="h-2 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 w-full absolute top-0 left-0"></div>

            <div class="p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 text-blue-600 mb-4 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Secure Payment</h2>
                    <p class="text-gray-500 text-sm mt-1">Complete your transaction securely</p>
                    <div class="mt-4 inline-block px-4 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-blue-700 font-semibold text-lg">
                        $10.00 <span class="text-xs font-normal text-blue-500 ml-1">USD</span>
                    </div>
                </div>
                
                <form id="payment-form">
                    <div id="layout-grid" class="grid grid-cols-1 gap-8">
                        
                        <!-- LEFT COLUMN: Payment Methods -->
                        <div id="methods-column" class="hidden space-y-4">
                            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">Select Method</h3>
                            
                            <!-- Saved Cards List -->
                            <div id="saved-cards-list" class="space-y-3">
                                <!-- Cards injected here -->
                            </div>

                            <div class="relative py-2">
                                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                                <div class="relative flex justify-center text-sm"><span class="px-2 bg-white text-gray-400 font-medium">OR</span></div>
                            </div>

                            <!-- Add New Card Option -->
                            <label class="group relative flex items-center p-4 cursor-pointer rounded-xl border border-gray-200 hover:border-blue-400 hover:bg-blue-50/30 transition-all duration-200 bg-white shadow-sm">
                                <input type="radio" name="payment_method" value="new" checked class="custom-radio peer sr-only">
                                <div class="w-full h-full absolute inset-0 rounded-xl border-2 border-transparent peer-checked:border-blue-500 pointer-events-none transition-all duration-200 peer-checked:bg-blue-50/50"></div>
                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-blue-600 peer-checked:bg-blue-600 mr-3 flex items-center justify-center transition-colors radio-circle relative z-10">
                                   <div class="w-2 h-2 rounded-full bg-white opacity-0 peer-checked:opacity-100 radio-dot"></div>
                                </div>
                                <span class="font-semibold text-gray-700 group-hover:text-blue-700 transition-colors relative z-10">Pay with New Card</span>
                            </label>
                        </div>

                        <!-- RIGHT COLUMN: Payment Details -->
                        <div id="details-column" class="space-y-6">
                            
                            <!-- Case 1: New Card Form -->
                            <div id="new-card-section" class="transition-all duration-300">
                                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Card Details</label>
                                    <div id="payment-element" class="min-h-[40px] p-1">
                                        <!-- Stripe Elements -->
                                    </div>
                                </div>
                                
                                <div class="mt-4 flex items-center">
                                    <label class="flex items-center space-x-3 cursor-pointer group">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" id="save-card" class="peer sr-only form-checkbox">
                                            <div class="w-5 h-5 bg-white border-2 border-gray-300 rounded peer-checked:bg-blue-600 peer-checked:border-blue-600 transition-colors"></div>
                                            <svg class="w-3 h-3 text-white absolute top-1 left-1 opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                        <span class="text-sm text-gray-600 group-hover:text-gray-900 transition-colors select-none">Save this card securely</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Case 2: Selected Saved Card Summary -->
                            <div id="saved-card-summary" class="hidden animate-fade-in text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                                <p class="text-gray-600 mb-2">Selected Payment Method</p>
                                <div class="text-xl font-bold text-gray-900 flex items-center justify-center gap-2">
                                    <span>💳</span> <span id="summary-brand" class="capitalize">Visa</span> •••• <span id="summary-last4">4242</span>
                                </div>
                                <p class="text-xs text-blue-600 mt-2 font-medium">Click "Pay Now" to confirm</p>
                            </div>

                            <!-- Submit Button -->
                            <button id="submit" class="w-full relative group overflow-hidden bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold py-4 px-4 rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 disabled:opacity-70 disabled:cursor-not-allowed mt-4">
                                <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                                <span id="button-text" class="relative flex items-center justify-center gap-2 text-lg">
                                    Pay $10.00
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </span>
                                <span id="spinner" class="hidden relative flex items-center justify-center gap-2">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processing...
                                </span>
                            </button>
                            
                            <!-- Message Container -->
                            <div id="payment-message" class="hidden mt-4 p-4 rounded-xl text-sm font-medium text-center shadow-sm animate-fade-in border"></div>

                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div class="mt-6 text-center">
                        <div class="flex items-center justify-center space-x-2 text-xs text-gray-400">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14h-2v-2h2v2zm0-4h-2V7h2v5z"></path></svg>
                            <span>Payments are encrypted and secured by Stripe</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        // Replace with your actual Stripe Publishable Key
        const stripe = Stripe("{{ $stripeKey }}"); 

        let elements;
        let dynamicSuccessUrl;
        let savedCards = [];
        let currentClientSecret;

        async function initialize() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Extract token and success_url from URL
            const urlParams = new URLSearchParams(window.location.search);
            const token = urlParams.get('token');
            const successUrlParam = urlParams.get('success_url');

            if (!token) {
                showMessage("Authentication token is missing.", "red");
                return;
            }

            // 0. Fetch saved cards
            try {
                const cardsResponse = await fetch("/api/payment/stripe/cards", {
                    headers: { "Authorization": "Bearer " + token }
                });
                if (cardsResponse.ok) {
                    const cardsData = await cardsResponse.json();
                    console.log("Raw Cards API Response:", cardsData);
                    
                    // Robust extraction logic
                    if (cardsData.data && Array.isArray(cardsData.data.data)) {
                        savedCards = cardsData.data.data; // Standard Stripe Collection wrapped in Resource
                    } else if (cardsData.data && Array.isArray(cardsData.data)) {
                        savedCards = cardsData.data; // Simple array wrapper
                    } else if (Array.isArray(cardsData)) {
                        savedCards = cardsData; // Direct array
                    } else {
                        console.warn("Could not find cards array in response");
                        savedCards = [];
                    }

                    console.log("Parsed Saved Cards:", savedCards);
                    
                    if (savedCards.length > 0) {
                        renderSavedCards();
                    } else {
                        console.log("No saved cards found to render.");
                    }
                } else {
                    console.error("Failed to fetch cards. Status:", cardsResponse.status);
                }
            } catch (e) {
                console.error("Failed to fetch cards", e);
            }

            const requestBody = { 
                amount: 1000, 
                currency: 'usd',
                success_url: window.location.origin + "/payment/success/test"
            };
            
            // 1. Fetch the PaymentIntent client secret from backend
            const response = await fetch("/api/payment/stripe/payment-intent", {
                method: "POST",
                headers: { 
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "Authorization": "Bearer " + token 
                },
                body: JSON.stringify(requestBody),
            });

            if (!response.ok) {
                const errorText = await response.text();
                console.error("API Error Response:", errorText);
                try {
                     const errorJson = JSON.parse(errorText);
                     showMessage(errorJson.message || "Failed to initialize payment", "red");
                } catch(e) {
                     showMessage("Failed to initialize payment: " + response.statusText, "red");
                }
                return;
            }

            const responseText = await response.text();
            console.log("API Success Response:", responseText);

            let data;
            try {
                data = JSON.parse(responseText);
            } catch (e) {
                console.error("JSON Parse Error:", e);
                showMessage("Invalid server response", "red");
                return;
            }

            const client_secret = data.data.client_secret;
            dynamicSuccessUrl = data.data.success_url; // Store dynamic success_url
            currentClientSecret = client_secret; // Store client_secret globally

            if (!client_secret) {
                console.error("Missing client_secret in", data);
                showMessage("Server returned no client secret", "red");
                return;
            }

            const appearance = {
                theme: 'stripe',
                labels: 'floating',
                variables: {
                    colorPrimary: '#3b82f6',
                    fontFamily: 'Inter, system-ui, sans-serif',
                }
            };
            
            elements = stripe.elements({ appearance, clientSecret: client_secret });

            const paymentElement = elements.create("payment");
            paymentElement.mount("#payment-element");
        }

        function renderSavedCards() {
            const container = document.getElementById('main-container');
            const layoutGrid = document.getElementById('layout-grid');
            const methodsColumn = document.getElementById('methods-column');
            const list = document.getElementById('saved-cards-list');
            
            // ALWAYS Enable Split Layout (Pro Design)
            container.classList.replace('max-w-lg', 'max-w-5xl');
            // Ensure width update happens even if replace fails
            if (!container.classList.contains('max-w-5xl')) {
                container.classList.add('max-w-5xl');
                container.classList.remove('max-w-lg');
            }
            
            layoutGrid.classList.add('md:grid-cols-2');
            methodsColumn.classList.remove('hidden');

            let html = '';
            
            if (savedCards && savedCards.length > 0) {
                savedCards.forEach((card, index) => {
                    const last4 = card.card.last4;
                    const brand = card.card.brand;
                    const exp = `${card.card.exp_month}/${card.card.exp_year}`;
                    const cardIcon = brand.toLowerCase() === 'visa' ? '💳' : (brand.toLowerCase() === 'mastercard' ? '💳' : '💳');
                    
                    html += `
                        <label class="group relative flex items-center p-3 cursor-pointer rounded-xl border border-gray-200 hover:border-blue-500 hover:shadow-md transition-all duration-200 bg-white">
                            <input type="radio" name="payment_method" value="${card.id}" 
                                data-brand="${brand}" data-last4="${last4}"
                                class="custom-radio peer sr-only">
                            
                            <div class="w-full h-full absolute inset-0 rounded-xl border-2 border-transparent peer-checked:border-blue-500 pointer-events-none transition-all duration-200 peer-checked:bg-blue-50/50"></div>

                            <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-blue-600 peer-checked:bg-blue-600 mr-4 flex items-center justify-center transition-colors radio-circle flex-shrink-0 relative z-10">
                               <div class="w-2 h-2 rounded-full bg-white opacity-0 peer-checked:opacity-100 radio-dot"></div>
                            </div>
                            
                            <div class="flex-1 relative z-10">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="bg-gray-100 p-2 rounded-lg text-xl">${cardIcon}</div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-800 capitalize leading-tight">${brand} <span class="text-gray-400 font-normal ml-1">•••• ${last4}</span></span>
                                            <span class="text-xs text-gray-500 font-medium">Expires ${exp}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    `;
                });
            } else {
                 html = `<div class="p-4 text-center text-gray-400 text-sm border-2 border-dashed border-gray-200 rounded-xl mb-4">No saved cards found</div>`;
            }

            list.innerHTML = html;

            // Handle switching
            document.querySelectorAll('input[name="payment_method"]').forEach(input => {
                input.addEventListener('change', (e) => {
                    const newCardSection = document.getElementById('new-card-section');
                    const savedCardSummary = document.getElementById('saved-card-summary');
                    
                    if (e.target.value === 'new') {
                        newCardSection.classList.remove('hidden');
                        savedCardSummary.classList.add('hidden');
                    } else {
                        newCardSection.classList.add('hidden');
                        savedCardSummary.classList.remove('hidden');
                        
                        // Update summary details
                        if (e.target.dataset.brand) {
                             document.getElementById('summary-brand').textContent = e.target.dataset.brand;
                             document.getElementById('summary-last4').textContent = e.target.dataset.last4;
                        }
                    }
                });
            });
        }

        async function handleSubmit(e) {
            e.preventDefault();
            setLoading(true);

            const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value || 'new';

            // --- A. NEW CARD PAYMENT ---
            if (selectedPaymentMethod === 'new') {
                const saveCard = document.getElementById('save-card').checked;
                
                if (saveCard && currentClientSecret) {
                    // Extract Intent ID from client secret (format: pi_XXX_secret_YYY)
                    const paymentIntentId = currentClientSecret.split('_secret_')[0];
                    
                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const urlParams = new URLSearchParams(window.location.search);
                        const token = urlParams.get('token');

                        await fetch("/api/payment/stripe/update-payment-intent", {
                            method: "POST",
                            headers: { 
                                "Content-Type": "application/json",
                                "Accept": "application/json",
                                "Authorization": "Bearer " + token 
                            },
                            body: JSON.stringify({ 
                                payment_intent_id: paymentIntentId,
                                save_card: true
                            }),
                        });
                    } catch (e) {
                        console.error("Failed to update intent for saving card", e);
                    }
                }

                const { error } = await stripe.confirmPayment({
                    elements,
                    confirmParams: {
                        return_url: dynamicSuccessUrl || (window.location.origin + "/payment/success"),
                    },
                });

                if (error) {
                    showMessage(error.message, "red");
                }
            } 
            // --- B. SAVED CARD PAYMENT ---
            else {
                 if (!currentClientSecret) {
                     showMessage("Payment session expired. Refresh page.", "red");
                     setLoading(false);
                     return;
                 }
                 
                 // Confirm the PaymentIntent using the specific PaymentMethod ID
                 const { paymentIntent, error } = await stripe.confirmCardPayment(currentClientSecret, {
                     payment_method: selectedPaymentMethod,
                     return_url: dynamicSuccessUrl || (window.location.origin + "/payment/success"),
                 });
                 
                 if (error) {
                     showMessage(error.message, "red");
                 } else if (paymentIntent && paymentIntent.status === "succeeded") {
                      // Manual redirect for successful card payment
                      // Append params to mimic Stripe's default redirect behavior
                      const redirectUrl = new URL(dynamicSuccessUrl || (window.location.origin + "/payment/success"));
                      redirectUrl.searchParams.append('payment_intent', paymentIntent.id);
                      redirectUrl.searchParams.append('payment_intent_client_secret', paymentIntent.client_secret);
                      redirectUrl.searchParams.append('redirect_status', 'succeeded');
                      
                      window.location.href = redirectUrl.toString();
                 } else {
                      console.log("Payment status:", paymentIntent.status);
                 }
            }

            setLoading(false);
        }

        function showMessage(messageText, color = "blue") {
            const messageContainer = document.querySelector("#payment-message");

            messageContainer.classList.remove("hidden", "bg-red-50", "border-red-200", "text-red-700", "bg-blue-50", "border-blue-200", "text-blue-700");
            
            if (color === "red") {
                 messageContainer.classList.add("bg-red-50", "border-red-200", "text-red-700");
            } else {
                 messageContainer.classList.add("bg-blue-50", "border-blue-200", "text-blue-700");
            }

            messageContainer.textContent = messageText;
            messageContainer.classList.remove("hidden");

            setTimeout(function () {
                messageContainer.classList.add("hidden");
                messageContainer.textContent = "";
            }, 6000);
        }

        function setLoading(isLoading) {
            if (isLoading) {
                document.querySelector("#submit").disabled = true;
                document.querySelector("#spinner").classList.remove("hidden");
                document.querySelector("#button-text").classList.add("hidden");
            } else {
                document.querySelector("#submit").disabled = false;
                document.querySelector("#spinner").classList.add("hidden");
                document.querySelector("#button-text").classList.remove("hidden");
            }
        }

        document.querySelector("#payment-form").addEventListener("submit", handleSubmit);

        initialize();
    </script>
</body>
</html>
