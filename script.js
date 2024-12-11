// Handle Review Submission
document.getElementById('reviewForm').addEventListener('submit', function (e) {
  e.preventDefault();

  const reviewText = document.getElementById('reviewText').value;
  if (reviewText.trim() === "") {
    alert("Review cannot be empty!");
    return;
  }

  const reviewList = document.getElementById('reviewList');
  const newReview = document.createElement('li');
  newReview.textContent = reviewText;
  reviewList.appendChild(newReview);

  document.getElementById('reviewText').value = "";
  alert("Your review has been posted!");
});
