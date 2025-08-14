document.addEventListener("alpine:init", () => {
    Alpine.data("notes", () => ({
        defaultParams: {
            id: null,
            title: '',
            description: '',
            tag_id: null,
            is_favorite: false
        },
        defaultTagParams: {
            id: null,
            name: '',
            color: '#3b82f6',
            description: ''
        },
        isAddNoteModal: false,
        isDeleteNoteModal: false,
        isViewNoteModal: false,
        isTagModal: false,
        isDeleteTagModal: false,
        params: {
            id: null,
            title: '',
            description: '',
            tag_id: null,
            is_favorite: false
        },
        tagParams: {
            id: null,
            name: '',
            color: '#3b82f6',
            description: ''
        },
        isShowNoteMenu: false,
        notesList: [],
        tagsList: [],
        filterdNotesList: [],
        selectedTab: 'all',
        deletedNote: null,
        tagToDelete: null,
        selectedNote: {
            id: null,
            title: '',
            description: '',
            tag: '',
            tag_color: '',
            user: '',
            date: '',
            isFav: false
        },
        loading: false,

        init() {
            this.loadTags();
            this.loadNotes();
        },

        async loadTags() {
            try {
                const response = await fetch('/tags', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.tagsList = data.tags.map(tag => ({
            id: Number(tag.id),  // Convertir a número
            name: tag.name,
            color: tag.color
        }));
                } else {
                    throw new Error('Failed to load tags');
                }
            } catch (error) {
                console.error('Error loading tags:', error);
                this.showMessage('Error loading tags', 'error');
            }
        },

        async loadNotes(filter = 'all') {
    this.loading = true;
    try {
        // Construye la URL correctamente
        let url = '/notes';
        if (filter && filter !== 'all') {
            url += `?filter=${encodeURIComponent(filter)}`;
        }
console.log("Filtering by:", filter);

        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            this.notesList = data.notes;
            this.tagsList = data.tags;
            this.searchNotes();

            console.log("Response data:", data);

        } else {
            throw new Error('Failed to load notes');
        }
    } catch (error) {
        console.error('Error loading notes:', error);
        this.showMessage('Error loading notes', 'error');
    } finally {
        this.loading = false;
    }
},

searchNotes() {
    if (this.selectedTab === 'fav') {
        this.filterdNotesList = this.notesList.filter(note => note.is_favorite === true);
    } else if (this.selectedTab === 'all') {
        this.filterdNotesList = this.notesList;
    } else {
        // Filtra por nombre del tag
        this.filterdNotesList = this.notesList.filter(note => 
            note.tag && note.tag.toLowerCase() === this.selectedTab.toLowerCase()
        );
    }
},


        async saveNote() {
            if (!this.params.title.trim()) {
                this.showMessage('Title is required.', 'error');
                return false;
            }

            this.loading = true;
            try {
                const url = this.params.id ? `/notes/${this.params.id}` : '/notes';
                const method = this.params.id ? 'PUT' : 'POST';
                
                const formData = new FormData();
                formData.append('title', this.params.title);
                formData.append('description', this.params.description || '');
                if (this.params.tag_id) {
                    formData.append('tag_id', this.params.tag_id);
                }
                formData.append('is_favorite', this.params.is_favorite ? '1' : '0');
                
                if (this.params.id) {
                    formData.append('_method', 'PUT');
                }

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                if (response.ok) {
                    const data = await response.json();
                    this.showMessage(data.message || 'Note saved successfully.');
                    this.isAddNoteModal = false;
                    this.resetParams();
                    await this.loadNotes(this.selectedTab);
                } else {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Failed to save note');
                }
            } catch (error) {
                console.error('Error saving note:', error);
                this.showMessage('Error saving note: ' + error.message, 'error');
            } finally {
                this.loading = false;
            }
        },

       async saveTag() {
    try {
        const url = this.tagParams.id ? `/tags/${this.tagParams.id}` : '/tags';
        const method = this.tagParams.id ? 'PUT' : 'POST';
        
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        };

        const response = await fetch(url, {
            method: 'POST', // Siempre POST pero con _method para PUT
            headers: headers,
            body: JSON.stringify({
                ...this.tagParams,
                _method: this.tagParams.id ? 'PUT' : 'POST'
            })
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Request failed');
        }

        const data = await response.json();
        this.showMessage(data.message || 'Operación exitosa');
        this.isTagModal = false;
        await this.loadTags();
    } catch (error) {
        console.error('Error saving tag:', error);
        this.showMessage(`Error: ${error.message}`, 'error');
    }
},
async tabChanged(type) {
    this.selectedTab = type;
    await this.loadNotes(type);
    this.isShowNoteMenu = false;
},

        async setFav(note) {
            try {
                const response = await fetch(`/notes/${note.id}/toggle-favorite`, {
                    method: 'PATCH',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    // Update in local list
                    let item = this.filterdNotesList.find((d) => d.id === note.id);
                    if (item) {
                        item.isFav = data.is_favorite;
                    }
                    // Also update in main list
                    let mainItem = this.notesList.find((d) => d.id === note.id);
                    if (mainItem) {
                        mainItem.isFav = data.is_favorite;
                    }
                    this.searchNotes();
                    this.showMessage(data.message);
                } else {
                    throw new Error('Failed to update favorite status');
                }
            } catch (error) {
                console.error('Error updating favorite:', error);
                this.showMessage('Error updating favorite status', 'error');
            }
        },

        async setTag(note, tagId) {
            try {
                const response = await fetch(`/notes/${note.id}/update-tag`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ tag_id: tagId })
                });

                if (response.ok) {
                    const data = await response.json();
                    // Update in local lists
                    [this.notesList, this.filterdNotesList].forEach(list => {
                        let item = list.find((d) => d.id === note.id);
                        if (item) {
                            item.tag = data.tag?.name || '';
                            item.tag_color = data.tag?.color || '';
                        }
                    });
                    this.searchNotes();
                    this.showMessage(data.message);
                } else {
                    throw new Error('Failed to update tag');
                }
            } catch (error) {
                console.error('Error updating tag:', error);
                this.showMessage('Error updating tag', 'error');
            }
        },

        deleteNoteConfirm(note) {
            this.deletedNote = note;
            this.isDeleteNoteModal = true;
        },

        confirmDeleteTag(tag) {
            this.tagToDelete = tag;
            this.isDeleteTagModal = true;
        },

        viewNote(note) {
            this.selectedNote = {
                id: note.id,
                title: note.title,
                description: note.description,
                tag: note.tag,
                tag_color: note.tag_color,
                user: note.user,
                date: note.date,
                isFav: note.isFav
            };
            this.isViewNoteModal = true;
        },

       editNote(note = null) {
    this.isShowNoteMenu = false;
    this.resetParams();
    
    if (note) {
        this.params = {
            id: note.id,
            title: note.title,
            description: note.description,
            tag_id: note.tag_id || null,  // Usamos el ID del tag
            is_favorite: note.isFav || false
        };
    }
    this.isAddNoteModal = true;
},

        openTagModal(tag = null) {
            this.resetTagParams();
            if (tag) {
                this.tagParams = {
                    id: tag.id,
                    name: tag.name,
                    color: tag.color,
                    description: tag.description || ''
                };
            }
            this.isTagModal = true;
        },

        async deleteNote() {
            if (!this.deletedNote) return;

            this.loading = true;
            try {
                const response = await fetch(`/notes/${this.deletedNote.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    this.showMessage(data.message || 'Note deleted successfully.');
                    this.isDeleteNoteModal = false;
                    this.deletedNote = null;
                    await this.loadNotes(this.selectedTab);
                } else {
                    throw new Error('Failed to delete note');
                }
            } catch (error) {
                console.error('Error deleting note:', error);
                this.showMessage('Error deleting note', 'error');
            } finally {
                this.loading = false;
            }
        },

     async deleteTag() {
    if (!this.tagToDelete) return;

    try {
        const response = await fetch(`/tags/${this.tagToDelete.id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        if (!response.ok) {
            throw new Error('Failed to delete tag');
        }

        const data = await response.json();
        this.showMessage(data.message || 'Tag eliminado exitosamente');
        this.isDeleteTagModal = false;
        this.tagToDelete = null;
        await this.loadTags();
    } catch (error) {
        console.error('Error deleting tag:', error);
        this.showMessage('Error deleting tag: ' + error.message, 'error');
    }
},

        resetParams() {
            this.params = JSON.parse(JSON.stringify(this.defaultParams));
        },

        resetTagParams() {
            this.tagParams = JSON.parse(JSON.stringify(this.defaultTagParams));
        },

        showMessage(msg = '', type = 'success') {
            if (window.Swal) {
                const toast = window.Swal.mixin({
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 3000,
                });
                toast.fire({
                    icon: type,
                    title: msg,
                    padding: '10px 20px',
                });
            } else {
                // Fallback if no SweetAlert
                alert(msg);
            }
        }
    }));
});
