export const fetchArticles = async (page = 1) => {
    const response = await fetch(`http://localhost:8000/api/articles?page=${page}`);
    return await response.json();
};