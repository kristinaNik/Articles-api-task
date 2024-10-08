import React, { useState, useEffect } from 'react';
import { fetchArticles } from '../api/articles'; // Import the fetchArticles function

function ArticleList({ searchCriteria }) {
    const [articles, setArticles] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [pagination, setPagination] = useState({
        current_page: 1,
        total_pages: 1,
    });

    const loadArticles = async (page = 1) => {
        setLoading(true);
        try {
            const data = await fetchArticles(page, searchCriteria);

            setArticles(data.data);
            setPagination({
                current_page: data.pagination.current_page,
                total_pages: Math.ceil(data.pagination.total / data.pagination.per_page),
            });
        } catch (err) {
            setError('Failed to fetch articles');
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        loadArticles();
    }, [searchCriteria]);

    const handlePageChange = (page) => {
        if (page > 0 && page <= pagination.total_pages) {
            loadArticles(page);
        }
    };

    if (loading) return <p>Loading...</p>;
    if (error) return <p>{error}</p>;

    return (
        <div className="article-list">
            <h2>Article List</h2>
            {articles.length > 0 ? (
                <div>
                    <ul className="list-group">
                        {articles.map(article => (
                            <li key={article.id} className="list-group-item">
                                <a href={article.url} target="_blank" rel="noopener noreferrer">
                                    {article.title}
                                </a>
                                <p>{article.description}</p>
                                <small>Source: {article.source}</small>
                                {article.author && <p>Author: {article.author}</p>}
                                {article.category && <p>Category: {article.category}</p>}
                                <p>Published at: {new Date(article.published_at).toLocaleString()}</p>
                            </li>
                        ))}
                    </ul>
                    <div className="pagination">
                        <button
                            disabled={pagination.current_page === 1}
                            onClick={() => handlePageChange(pagination.current_page - 1)}
                        >
                            &laquo; Previous
                        </button>
                        <span>Page {pagination.current_page} of {pagination.total_pages}</span>
                        <button
                            disabled={pagination.current_page === pagination.total_pages}
                            onClick={() => handlePageChange(pagination.current_page + 1)}
                        >
                            Next &raquo;
                        </button>
                    </div>
                </div>
            ) : (
                <p>No articles found.</p>
            )}
        </div>
    );
}

export default ArticleList;