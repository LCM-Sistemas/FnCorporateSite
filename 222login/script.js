"use strict";
window.addEventListener("DOMContentLoaded", () => {
    const formHandler = new FormHandler("#loginForm");
});

class FormHandler {
    /**
     * @param el CSS selector of the form element
     */
    constructor(el) {
        this.el = document.querySelector(el);
        this.setupListeners();
    }

    setupListeners() {
        if (this.el) {
            this.el.addEventListener("submit", this.handleSubmit.bind(this));
        }
    }

    async handleSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(this.el);
        const jsonData = {};

        formData.forEach((value, key) => {
            jsonData[key] = value;
        });

        try {
            const response = await fetch(this.el.action, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(jsonData),
            });

            const result = await response.text();
            if (result.includes("Dados enviados com Sucesso")) {
                document.getElementById("successMessage").style.display = "block";
                document.getElementById("errorMessage").style.display = "none";
                this.el.reset();
            } else {
                document.getElementById("errorMessage").style.display = "block";
                document.getElementById("successMessage").style.display = "none";
            }
        } catch (error) {
            console.error("Error:", error);
            document.getElementById("errorMessage").style.display = "block";
            document.getElementById("successMessage").style.display = "none";
        }
    }
}
