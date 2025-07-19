// assets/js/calendario/etiquetasManager.js

export default class EtiquetasManager {
    constructor() {
        this.etiquetas = [];
        this.tomSelectInstance = null;
        this.init();
    }

    async init() {
        await this.cargarEtiquetas();
        this.initTomSelect();
    }

    async cargarEtiquetas() {
        try {
            const response = await fetch('/etiquetas', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'include'
            });
            
            if (!response.ok) throw new Error('Error al cargar etiquetas');
            
            this.etiquetas = await response.json();
            return this.etiquetas;
        } catch (error) {
            console.error('Error:', error);
            throw error;
        }
    }

    initTomSelect() {
        const element = document.getElementById('etiquetasEvento');
        if (!element) return;

        this.tomSelectInstance = new TomSelect(element, {
            plugins: ['remove_button'],
            placeholder: 'Selecciona etiquetas...',
            options: this.etiquetas.map(etiqueta => ({
                value: etiqueta.id,
                text: etiqueta.nombre
            }))
        });
    }

    async crearEtiqueta(nombre, color, icono = '') {
        try {
            const response = await fetch('/etiquetas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'include',
                body: JSON.stringify({ nombre, color, icono })
            });
            
            if (!response.ok) throw new Error('Error al crear etiqueta');
            
            const nuevaEtiqueta = await response.json();
            this.etiquetas.push(nuevaEtiqueta);
            
            if (this.tomSelectInstance) {
                this.tomSelectInstance.addOption({
                    value: nuevaEtiqueta.id,
                    text: nuevaEtiqueta.nombre
                });
            }
            
            return nuevaEtiqueta;
        } catch (error) {
            console.error('Error:', error);
            throw error;
        }
    }

    async actualizarEtiqueta(id, nombre, color, icono = '') {
        try {
            const response = await fetch(`/etiquetas/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'include',
                body: JSON.stringify({ nombre, color, icono })
            });
            
            if (!response.ok) throw new Error('Error al actualizar etiqueta');
            
            const etiquetaActualizada = await response.json();
            const index = this.etiquetas.findIndex(e => e.id === id);
            
            if (index !== -1) {
                this.etiquetas[index] = etiquetaActualizada;
            }
            
            if (this.tomSelectInstance) {
                this.tomSelectInstance.updateOption(id, {
                    value: etiquetaActualizada.id,
                    text: etiquetaActualizada.nombre
                });
            }
            
            return etiquetaActualizada;
        } catch (error) {
            console.error('Error:', error);
            throw error;
        }
    }

    async eliminarEtiqueta(id) {
        try {
            const response = await fetch(`/etiquetas/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'include'
            });
            
            if (!response.ok) throw new Error('Error al eliminar etiqueta');
            
            this.etiquetas = this.etiquetas.filter(e => e.id !== id);
            
            if (this.tomSelectInstance) {
                this.tomSelectInstance.removeOption(id);
            }
            
            return true;
        } catch (error) {
            console.error('Error:', error);
            throw error;
        }
    }

    getEtiquetas() {
        return this.etiquetas;
    }

    getTomSelectInstance() {
        return this.tomSelectInstance;
    }
}