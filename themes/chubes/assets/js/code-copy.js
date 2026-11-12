/**
 * Code Block Copy Functionality
 * 
 * Handles clipboard copy for code blocks with visual feedback.
 * Uses navigator.clipboard API with icon swap on success.
 */

document.addEventListener('DOMContentLoaded', function() {
	const copyButtons = document.querySelectorAll('.code-copy-btn');

	copyButtons.forEach(function(button) {
		button.addEventListener('click', function() {
			const codeBlock = this.closest('.wp-block-code');
			const codeElement = codeBlock.querySelector('code');
			const textToCopy = codeElement.textContent;

			navigator.clipboard.writeText(textToCopy).then(function() {
				const useElement = button.querySelector('use');
				const originalHref = useElement.getAttribute('href');
				const checkHref = originalHref.replace('#icon-copy', '#icon-check');

				useElement.setAttribute('href', checkHref);
				button.classList.add('copied');

				setTimeout(function() {
					useElement.setAttribute('href', originalHref);
					button.classList.remove('copied');
				}, 2000);
			});
		});
	});
});
