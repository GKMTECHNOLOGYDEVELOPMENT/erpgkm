document.addEventListener('alpine:init', () => {
    Alpine.data('wizardDespacho', () => ({
        currentStep: 0,
        steps: [
            { title: 'Documento', icon: 'fas fa-file-alt' },
            { title: 'Direcciones', icon: 'fas fa-map-marker-alt' },
            { title: 'Cliente', icon: 'fas fa-users' },
            { title: 'Artículos', icon: 'fas fa-box' },
        ],
        // INICIALIZAR CON ARRAY VACÍO - ESTO ES LO QUE CAUSABA EL PROBLEMA
        articulos: [],
        searchTerm: '',
        subtotal: 0,
        igv: 0,
        total: 0,

        init() {
            this.calcularTotales();
        },

        nextStep() {
            if (this.currentStep < 3) {
                this.currentStep++;
            }
        },

        previousStep() {
            if (this.currentStep > 0) {
                this.currentStep--;
            }
        },

        agregarArticulo() {
            const nuevoId = this.articulos.length > 0 ? Math.max(...this.articulos.map((a) => a.id)) + 1 : 1;

            this.articulos.push({
                id: nuevoId,
                codigo: '',
                descripcion: '',
                stock: 0,
                unidad: 'Unidad',
                precio: 0,
                cantidad: 1, // Cambiado a 1 por defecto en lugar de 0
            });
            
            this.calcularTotales();
        },

        eliminarArticulo(index) {
            this.articulos.splice(index, 1);
            this.calcularTotales();
        },

        calcularTotales() {
            this.subtotal = this.articulos.reduce((sum, articulo) => {
                return sum + (articulo.precio * articulo.cantidad);
            }, 0);

            this.igv = this.subtotal * 0.18;
            this.total = this.subtotal + this.igv;
        },
    }));
});