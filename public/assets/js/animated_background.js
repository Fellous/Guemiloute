document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("background-animation");

    // Générer des bulles dynamiques
    for (let i = 0; i < 30; i++) { // Augmentez le nombre pour plus de bulles
        const bubble = document.createElement("div");
        bubble.className = "bubble";

        // Propriétés dynamiques aléatoires
        const size = Math.random() * 80 + 20; // Taille entre 20px et 100px
        const startX = Math.random() * 100; // Position de départ sur X (0% à 100%)
        const startY = Math.random() * 100; // Position de départ sur Y (0% à 100%)
        const midX = Math.random() * 200 - 50; // Mouvement vers le milieu (trajectoire variée sur X)
        const midY = Math.random() * 200 - 50; // Mouvement vers le milieu (trajectoire variée sur Y)
        const endX = Math.random() * 100; // Position finale sur X (0% à 100%)
        const endY = Math.random() * 100 - 150; // Position finale sur Y (sortie de l'écran)

        // Appliquer les styles via CSS custom properties
        bubble.style.width = `${size}px`;
        bubble.style.height = `${size}px`;
        bubble.style.setProperty("--scale", Math.random() * 0.5 + 0.75); // Échelle entre 0.75 et 1.25
        bubble.style.setProperty("--start-x", `${startX}vw`);
        bubble.style.setProperty("--start-y", `${startY}vh`);
        bubble.style.setProperty("--mid-x", `${midX}vw`);
        bubble.style.setProperty("--mid-y", `${midY}vh`);
        bubble.style.setProperty("--end-x", `${endX}vw`);
        bubble.style.setProperty("--end-y", `${endY}vh`);
        bubble.style.animationDuration = `${Math.random() * 10 + 15}s`; // Durée aléatoire entre 15s et 25s

        container.appendChild(bubble);
    }
});
