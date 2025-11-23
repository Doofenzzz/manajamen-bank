<!-- Modal Component untuk Laravel -->
<div id="statusModal" class="fixed inset-0 z-50 hidden items-center justify-center" style="position: fixed; z-index: 9999;">
    <!-- Backdrop -->
    <div id="modalBackdrop" class="absolute inset-0 bg-black opacity-0 transition-opacity duration-500" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5);"></div>
    
    <!-- Modal Container -->
    <div id="modalContainer" class="relative bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform scale-90 opacity-0 translate-y-4 transition-all duration-500" style="position: relative; background: white; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); padding: 2rem; max-width: 28rem; width: 100%; margin: 0 1rem;">
        <!-- Icon Container -->
        <div class="flex justify-center mb-6" style="display: flex; justify-content: center; margin-bottom: 1.5rem;">
            <div class="relative" style="position: relative;">
                <!-- Loading/Status Circle -->
                <div id="statusCircle" class="w-24 h-24 rounded-full border-4 border-gray-200 flex items-center justify-center transition-all duration-700" style="width: 6rem; height: 6rem; border-radius: 9999px; border: 4px solid #e5e7eb; display: flex; align-items: center; justify-content: center; transition: all 0.7s;">
                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" class="w-full h-full rounded-full border-4 border-gray-200 border-t-blue-500" style="width: 100%; height: 100%; border-radius: 9999px; border: 4px solid #e5e7eb; border-top-color: #3b82f6; position: absolute;"></div>
                    
                    <!-- Success Icon -->
                    <svg id="successIcon" class="w-12 h-12 text-green-500 hidden" style="width: 3rem; height: 3rem; color: #10b981; display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                    
                    <!-- Error Icon -->
                    <svg id="errorIcon" class="w-12 h-12 text-red-500 hidden" style="width: 3rem; height: 3rem; color: #ef4444; display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Text Content -->
        <div id="modalContent" class="text-center opacity-0 translate-y-4 transition-all duration-700" style="text-align: center; opacity: 0; transform: translateY(1rem); transition: all 0.7s;">
            <h2 id="modalTitle" class="text-2xl font-bold text-gray-800 mb-3" style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 0.75rem;">Memproses...</h2>
            <p id="modalMessage" class="text-gray-600 mb-6" style="color: #4b5563; margin-bottom: 1.5rem;">Mohon tunggu sebentar...</p>
            
            <!-- OK Button -->
            <button id="modalOkButton" class="hidden px-8 py-3 rounded-lg font-semibold text-white transition-all duration-300 transform hover:scale-105 hover:shadow-lg active:scale-95" style="display: none; padding: 0.75rem 2rem; border-radius: 0.5rem; font-weight: 600; color: white; transition: all 0.3s; border: none; cursor: pointer;">
                OK
            </button>
        </div>
    </div>
</div>

<style>
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes popIn {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    50% {
        transform: scale(1.15);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(15px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.spin-animation {
    animation: spin 0.8s linear infinite;
}

.pop-in-animation {
    animation: popIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.fade-in-up-animation {
    animation: fadeInUp 0.6s ease-out 0.4s both;
}
</style>

<script>
class StatusModal {
    constructor() {
        this.modal = document.getElementById('statusModal');
        this.backdrop = document.getElementById('modalBackdrop');
        this.container = document.getElementById('modalContainer');
        this.circle = document.getElementById('statusCircle');
        this.spinner = document.getElementById('loadingSpinner');
        this.successIcon = document.getElementById('successIcon');
        this.errorIcon = document.getElementById('errorIcon');
        this.content = document.getElementById('modalContent');
        this.title = document.getElementById('modalTitle');
        this.message = document.getElementById('modalMessage');
        this.okButton = document.getElementById('modalOkButton');
        
        this.okButton.addEventListener('click', () => this.close());
        this.backdrop.addEventListener('click', () => this.close());
    }
    
    show(status, message) {
        this.reset();
        this.modal.style.display = 'flex';
        
        setTimeout(() => {
            this.backdrop.style.opacity = '0.5';
            this.container.style.transform = 'scale(1) translateY(0)';
            this.container.style.opacity = '1';
        }, 10);
        
        this.spinner.classList.add('spin-animation');
        
        setTimeout(() => {
            this.showResult(status, message);
        }, 800);
    }
    
    showResult(status, message) {
        const isSuccess = status === 'success';
        
        this.spinner.classList.remove('spin-animation');
        this.spinner.style.display = 'none';
        
        this.circle.style.borderColor = isSuccess ? '#bbf7d0' : '#fecaca';
        this.circle.style.backgroundColor = isSuccess ? '#f0fdf4' : '#fef2f2';
        
        if (isSuccess) {
            this.successIcon.style.display = 'block';
            this.successIcon.classList.add('pop-in-animation');
            this.okButton.style.backgroundColor = '#3b82f6';
        } else {
            this.errorIcon.style.display = 'block';
            this.errorIcon.classList.add('pop-in-animation');
            this.okButton.style.backgroundColor = '#ef4444';
        }
        
        this.title.textContent = isSuccess ? 'Berhasil!' : 'Gagal!';
        this.message.textContent = message;
        
        setTimeout(() => {
            this.content.style.opacity = '1';
            this.content.style.transform = 'translateY(0)';
        }, 200);
        
        setTimeout(() => {
            this.okButton.style.display = 'inline-block';
            this.okButton.classList.add('fade-in-up-animation');
        }, 400);
    }
    
    close() {
        this.backdrop.style.opacity = '0';
        this.container.style.transform = 'scale(0.9) translateY(1rem)';
        this.container.style.opacity = '0';
        
        setTimeout(() => {
            this.modal.style.display = 'none';
        }, 500);
    }
    
    reset() {
        this.spinner.style.display = 'block';
        this.spinner.classList.remove('spin-animation');
        this.successIcon.style.display = 'none';
        this.successIcon.classList.remove('pop-in-animation');
        this.errorIcon.style.display = 'none';
        this.errorIcon.classList.remove('pop-in-animation');
        this.okButton.style.display = 'none';
        this.okButton.classList.remove('fade-in-up-animation');
        this.circle.style.borderColor = '#e5e7eb';
        this.circle.style.backgroundColor = 'transparent';
        this.content.style.opacity = '0';
        this.content.style.transform = 'translateY(1rem)';
        this.title.textContent = 'Memproses...';
        this.message.textContent = 'Mohon tunggu sebentar...';
    }
}

const statusModal = new StatusModal();

function showStatusModal(status, message) {
    statusModal.show(status, message);
}
</script>