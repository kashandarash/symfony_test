function createFloatingDiv() {
    const div = document.createElement("div");
    div.className = "floating-div";
    div.textContent = "✨";

    // Random start position
    const x = Math.random() * (window.innerWidth - 40);
    const y = Math.random() * (window.innerHeight - 40);

    div.style.left = `${x}px`;
    div.style.top = `${y}px`;

    document.body.appendChild(div);

    // Trigger floating effect with CSS transition
    requestAnimationFrame(() => {
        div.style.transform = "translateY(-100px)";
        div.style.opacity = "0";
    });

    // Remove after animation
    setTimeout(() => div.remove(), 2000);
}

// Create one every 2 seconds
setInterval(createFloatingDiv, 2000);