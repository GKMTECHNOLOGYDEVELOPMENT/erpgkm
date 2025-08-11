document.addEventListener('alpine:init', () => {
    Alpine.data('notes', () => ({
        defaultParams: {
            id: null,
            title: '',
            description: '',
            tag_id: null,
            is_favorite: false,
        },
        isAddNoteModal: false,
        isDeleteNoteModal: false,
        isViewNoteModal: false,
        params: {
            id: null,
            title: '',
            description: '',
            tag_id: null,
            is_favorite: false,
        },
        isShowNoteMenu: false,
        notesList: [],
        tagsList: [],
        filterdNotesList: [],
        selectedTab: 'all',
        deletedNote: null,
        selectedNote: {
            id: null,
            title: '',
            description: '',
            tag: '',
            tag_color: '',
            user: '',
            date: '',
            isFav: false,
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
                        Accept: 'application/json',
                    },
                });

                if (response.ok) {
                    const data = await response.json();
                    this.tagsList = data.tags;
                }
            } catch (error) {
                console.error('Error loading tags:', error);
                this.showMessage('Error loading tags', 'error');
            }
        },

        async loadNotes(filter = 'all') {
            this.loading = true;
            try {
                const url = filter && filter !== 'all' ? `/notes?filter=${filter}` : '/notes';
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        Accept: 'application/json',
                    },
                });

                if (response.ok) {
                    const data = await response.json();
                    this.notesList = data.notes.map((note) => ({
                        id: note.id,
                        title: note.title,
                        description: note.description,
                        isFav: note.is_favorite,
                        tag: note.tag,
                        tag_color: note.tag_color,
                        date: note.date,
                        user: note.user,
                    }));
                    this.searchNotes();
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
                this.filterdNotesList = this.notesList.filter((d) => d.isFav);
            } else if (this.selectedTab === 'all') {
                this.filterdNotesList = this.notesList;
            } else {
                this.filterdNotesList = this.notesList.filter((d) => d.tag === this.selectedTab);
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
                formData.append('tag_id', this.params.tag_id || '');
                formData.append('is_favorite', this.params.is_favorite ? '1' : '0');

                if (this.params.id) {
                    formData.append('_method', 'PUT');
                }

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: formData,
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
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
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

        async setTag(note, tagName) {
            try {
                const tag = this.tagsList.find((t) => t.name === tagName);
                const tag_id = tag ? tag.id : null;

                const response = await fetch(`/notes/${note.id}/update-tag`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        tag_id: tag_id,
                    }),
                });

                if (response.ok) {
                    const data = await response.json();
                    // Update in local lists
                    [this.notesList, this.filterdNotesList].forEach((list) => {
                        let item = list.find((d) => d.id === note.id);
                        if (item) {
                            item.tag = data.tag;
                            item.tag_color = data.tag_color;
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

        viewNote(note) {
            this.selectedNote = {
                id: note.id,
                title: note.title,
                description: note.description,
                tag: note.tag,
                tag_color: note.tag_color,
                user: note.user,
                date: note.date,
                isFav: note.isFav,
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
                    tag_id: this.tagsList.find((t) => t.name === note.tag)?.id || null,
                    is_favorite: note.isFav || false,
                };
            }
            this.isAddNoteModal = true;
        },

        async deleteNote() {
            if (!this.deletedNote) return;

            this.loading = true;
            try {
                const response = await fetch(`/notes/${this.deletedNote.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
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

        resetParams() {
            this.params = JSON.parse(JSON.stringify(this.defaultParams));
        },

        getTagNameById(tagId) {
            if (!tagId) return '';
            const tag = this.tagsList.find((t) => t.id === tagId);
            return tag ? tag.name : '';
        },

        getTagColorById(tagId) {
            if (!tagId) return '';
            const tag = this.tagsList.find((t) => t.id === tagId);
            return tag ? tag.color : '';
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
        },
    }));
});
