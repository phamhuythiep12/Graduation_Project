
const flashcardContainer = document.getElementById("flashcardContainer");
const addFlashcardBtn = document.getElementById("addFlashcardBtn");
const imagePreview = document.getElementById("imagePreview");

addFlashcardBtn.addEventListener("click", function() {
	
	const newFlashcard = document.createElement("div");
	newFlashcard.className = "flashcard";
	newFlashcard.innerHTML = `
			<div id="flashcardContainer">
			<div class="flashcard">
				<div class="front">
					<h3>Front of Flashcard</h3>
					<input type="file" class="frontTextarea" name="lesson_front_flashcard[]">
				</div>
				<div class="back">
					<h3>Back of Flashcard</h3>
					<textarea class="backTextarea" name="lesson_back_flashcard[]"></textarea>
				</div>
			</div>
		</div>
	`;
	flashcardContainer.appendChild(newFlashcard);
});
