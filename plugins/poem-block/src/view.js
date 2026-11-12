document.addEventListener('DOMContentLoaded', function () {
    console.log('Document loaded');
    const poemBlocks = document.querySelectorAll('.poem');
    let currentIndex = 0;

    if (poemBlocks.length > 0) {
        console.log('Poem blocks found:', poemBlocks.length);

        // Temporarily add navigation controls and page numbers to each poem block for height calculation
        poemBlocks.forEach((block, index) => {
            const navigation = document.createElement('div');
            navigation.className = 'poem-navigation temp-nav';
            const prevButton = document.createElement('button');
            prevButton.innerText = 'Previous';
            prevButton.className = 'poem-nav-button prev';
            const nextButton = document.createElement('button');
            nextButton.innerText = 'Next';
            nextButton.className = 'poem-nav-button next';
            const pageNumber = document.createElement('div');
            pageNumber.className = 'poem-page-number';
            pageNumber.innerText = `Page ${index + 1} of ${poemBlocks.length}`;

            navigation.appendChild(prevButton);
            navigation.appendChild(nextButton);
            block.appendChild(navigation);
            block.appendChild(pageNumber);
        });

        // Calculate the height of the longest poem block including the navigation buttons and page numbers
        let maxHeight = 0;
        poemBlocks.forEach((block) => {
            block.style.display = 'block';
            const height = block.offsetHeight;
            if (height > maxHeight) {
                maxHeight = height;
            }
        });

        // Remove the temporary navigation controls and page numbers
        document.querySelectorAll('.temp-nav').forEach((nav) => {
            nav.remove();
        });
        document.querySelectorAll('.poem-page-number').forEach((pageNumber) => {
            pageNumber.remove();
        });

        // Set all poem blocks to the height of the longest poem block
        poemBlocks.forEach((block, index) => {
            block.style.height = `${maxHeight}px`;
            if (index !== 0) {
                block.style.display = 'none';
            }

            // Add permanent navigation controls and page numbers to each poem block
            const navigation = document.createElement('div');
            navigation.className = 'poem-navigation';
            const prevButton = document.createElement('button');
            prevButton.innerText = 'Previous';
            prevButton.className = 'poem-nav-button prev';
            const nextButton = document.createElement('button');
            nextButton.innerText = 'Next';
            nextButton.className = 'poem-nav-button next';
            const pageNumber = document.createElement('div');
            pageNumber.className = 'poem-page-number';
            pageNumber.innerText = `${index + 1} of ${poemBlocks.length}`;

            navigation.appendChild(prevButton);
            navigation.appendChild(nextButton);
            block.appendChild(navigation);
            block.appendChild(pageNumber);

            console.log(`Navigation controls and page number added to poem block ${index + 1}`);
        });

        // Update page numbers on navigation
        function updatePageNumbers() {
            poemBlocks.forEach((block, index) => {
                const pageNumber = block.querySelector('.poem-page-number');
                if (pageNumber) {
                    pageNumber.innerText = `${index + 1} of ${poemBlocks.length}`;
                }
            });
        }

        // Event listeners for navigation buttons
        document.querySelectorAll('.poem-nav-button.prev').forEach((button) => {
            button.addEventListener('click', () => {
                console.log('Previous button clicked');
                poemBlocks[currentIndex].style.display = 'none';
                currentIndex = (currentIndex === 0) ? poemBlocks.length - 1 : currentIndex - 1;
                poemBlocks[currentIndex].style.display = 'block';
                updatePageNumbers();
            });
        });

        document.querySelectorAll('.poem-nav-button.next').forEach((button) => {
            button.addEventListener('click', () => {
                console.log('Next button clicked');
                poemBlocks[currentIndex].style.display = 'none';
                currentIndex = (currentIndex === poemBlocks.length - 1) ? 0 : currentIndex + 1;
                poemBlocks[currentIndex].style.display = 'block';
                updatePageNumbers();
            });
        });
    } else {
        console.log('No poem blocks found');
    }
});
