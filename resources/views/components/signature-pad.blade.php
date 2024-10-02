<div {{ $attributes }} wire:ignore x-data="{
    signaturePadId: $id('signature'),
    signaturePad: null,
    signature: @entangle($attributes->get('wire:model')),
    ratio: null,
    savedSignature: null, // Holds the signature before resize
    init() {
        this.resizeCanvas();
        this.signaturePad = new SignaturePad(this.$refs.canvas);
        if (this.signature) {
            this.signaturePad.fromDataURL(this.signature, { ratio: this.ratio });
        }
        this.signaturePad.addEventListener('endStroke', () => {
            this.signature = this.getDataUrlWithBackground();
        });
    },
    save() {
        this.signature = this.getDataUrlWithBackground();
        this.$dispatch('signature-saved', this.signaturePadId);
    },
    clear() {
        this.signaturePad.clear();
        this.signature = null;
        this.setBackground();
    },
    resizeCanvas() {
        // Ensure that signaturePad is initialized before accessing it
        if (this.signaturePad && !this.signaturePad.isEmpty()) {
            this.savedSignature = this.getDataUrlWithBackground();
        }

        this.ratio = Math.max(window.devicePixelRatio || 1, 1);
        this.$refs.canvas.width = this.$refs.canvas.offsetWidth * this.ratio;
        this.$refs.canvas.height = this.$refs.canvas.offsetHeight * this.ratio;
        this.$refs.canvas.getContext('2d').scale(this.ratio, this.ratio);
        this.setBackground();

        // Redraw the saved signature after resizing
        if (this.savedSignature) {
            this.signaturePad.fromDataURL(this.savedSignature, { ratio: this.ratio });
        }
    },
    setBackground() {
        const ctx = this.$refs.canvas.getContext('2d');
        ctx.fillStyle = '#ffffff'; // Set your desired background color
        ctx.fillRect(0, 0, this.$refs.canvas.width, this.$refs.canvas.height);
    },
    getDataUrlWithBackground() {
        const canvas = this.$refs.canvas;
        const ctx = canvas.getContext('2d');
        
        // Create a temporary canvas to draw the background
        const tempCanvas = document.createElement('canvas');
        tempCanvas.width = canvas.width;
        tempCanvas.height = canvas.height;
        const tempCtx = tempCanvas.getContext('2d');

        // Draw white background on the temporary canvas
        tempCtx.fillStyle = '#ffffff';
        tempCtx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);
        
        // Draw the existing signature on top
        tempCtx.drawImage(canvas, 0, 0);
        
        return tempCanvas.toDataURL('image/png'); // Return the image with background
    }
}" @resize.window="resizeCanvas" style="height: 200px;">

    <canvas x-ref="canvas" class="w-full h-full border-2 border-gray-300 border-dashed rounded-md"></canvas>

    <div class="flex mt-2 space-x-2 justify-end">
        <a href="#" x-on:click.prevent="clear()" class="text-sm font-medium text-gray-700 underline">
            {{ __("Clear") }}
        </a>

        <span x-data="{
            open: false,
            saved(e) {
                if (e.detail != this.signaturePadId) {
                    return;
                }
                this.open = true;
                setTimeout(() => { this.open = false }, 900);
            }
        }" x-show="open" @signature-saved.window="saved" x-transition
            class="text-sm font-medium text-green-700" style="display:none">
            Saved!
        </span>
    </div>
</div>
