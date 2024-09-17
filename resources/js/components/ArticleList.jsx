import React, { useState, useEffect } from 'react';
import axios from 'axios';

function ArticleList({ searchCriteria }) {
    const [articles, setArticles] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchArticles = async () => {
            setLoading(true);
            try {
                const response = await axios.get('http://localhost:8000/api/articles', {
                    params: searchCriteria,
                });
                setArticles(response.data.data);
            } catch (err) {
                console.error('Error fetching articles:', err);
                setError('Failed to fetch articles');
            } finally {
                setLoading(false);
            }
        };

        fetchArticles();
    }, [searchCriteria]);

    if (loading) return <p>Loading...</p>;
    if (error) return <p>{error}</p>;

    return (
        <div className="article-list">
            <h2>Article List</h2>
            {articles.length > 0 ? (
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
            ) : (
                <p>No articles found.</p>
            )}
        </div>
    );
}

export default ArticleList;