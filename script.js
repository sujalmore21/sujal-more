// Form Redirect
document.getElementById("webinarForm").addEventListener("submit", function(e) {
  e.preventDefault();
  alert("Thank you for registering! Webinar link sent to your email.");
  window.location.href = "thank-you.html"; // Or integrate EmailJS here
});

// FAQ Toggle
document.querySelectorAll(".faq-question").forEach(btn => {
  btn.addEventListener("click", () => {
    const answer = btn.nextElementSibling;
    answer.style.display = answer.style.display === "block" ? "none" : "block";
  });
});
