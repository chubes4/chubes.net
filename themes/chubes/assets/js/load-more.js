document.addEventListener("DOMContentLoaded", function () {
    if (typeof loadmore_params === "undefined") return;

    let page = parseInt(loadmore_params.current_page);
    const maxPage = parseInt(loadmore_params.max_page);
    const loadMoreBtn = document.querySelector("#load-more");
    const portfolioContainer = document.querySelector("#portfolio-container");

    if (!loadMoreBtn) return;

    loadMoreBtn.addEventListener("click", function () {
        if (page >= maxPage) {
            loadMoreBtn.style.display = "none";
            return;
        }

        page++;

        let xhr = new XMLHttpRequest();
        xhr.open("GET", loadmore_params.ajaxurl + "?action=load_more_portfolio&page=" + page, true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                let newItems = document.createElement("div");
                newItems.innerHTML = xhr.responseText;

                while (newItems.firstChild) {
                    portfolioContainer.appendChild(newItems.firstChild);
                }

                if (page >= maxPage) {
                    loadMoreBtn.style.display = "none";
                }
            }
        };
        xhr.send();
    });
});
