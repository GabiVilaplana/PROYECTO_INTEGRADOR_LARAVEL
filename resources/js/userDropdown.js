// resources/js/userDropdown.js
const UserDropdown = {
    dropdown: document.getElementById('user-dropdown'),
    profileIcon: document.querySelector('.icono-perfil'),
    rightHeader: document.querySelector('.right-header'),

    init() {
        if (!this.profileIcon) return;
        
        this.bindProfileDropdown(() => this.renderToggleDropdown());
    },

    renderToggleDropdown() {
        if (!this.dropdown) return;
        const isActive = this.dropdown.classList.contains("active");
        document.querySelectorAll(".user-dropdown").forEach(el => el.classList.remove("active"));
        if (!isActive) {
            this.dropdown.classList.add("active");
            document.addEventListener("click", this.renderCloseDropdownOnClickOutside.bind(this));
        }
    },

    renderCloseDropdownOnClickOutside(event) {
        if (
            this.dropdown &&
            this.profileIcon &&
            !this.dropdown.contains(event.target) &&
            !this.profileIcon.contains(event.target)
        ) {
            this.dropdown.classList.remove("active");
            document.removeEventListener("click", this.renderCloseDropdownOnClickOutside.bind(this));
        }
    },

    bindProfileDropdown(handler) {
        if (!this.profileIcon) return;
        this.profileIcon.addEventListener("click", (event) => {
            event.stopPropagation();
            handler();
        });
        this.rightHeader?.addEventListener("keydown", (event) => {
            if (event.key === " " || event.key === "Enter") {
                event.preventDefault();
                handler();
            }
        });
    }
};

// Iniciar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    UserDropdown.init();
});

export default UserDropdown;