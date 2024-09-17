import React, { useState, useEffect } from 'react';
import { fetchArticles } from '../api/articles';

function ArticleList() {
    const [articles, setArticles] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const getArticles = async () => {
            try {
                const articles = await fetchArticles();
                setArticles(articles);
            } catch (error) {
                setError('Failed to fetch articles');
            } finally {
                setLoading(false);
            }
        };

        getArticles();
    }, []);

    if (loading) return <div className="text-center">Loading...</div>;
    if (error) return <div className="alert alert-danger">{error}</div>;

    return (
        <div className="container">
            <h2 className="my-4">Article List</h2>
            {articles.length > 0 ? (
                <div className="list-group">
                    {articles.map(article => (
                        <div key={article.id} className="list-group-item list-group-item-action">
                            <h5 className="mb-1">
                                <a href={article.url} target="_blank" rel="noopener noreferrer" className="text-decoration-none">
                                    {article.title}
                                </a>
                            </h5>
                            <p className="mb-1">{article.description}</p>
                            <small className="text-muted">Source: {article.source}</small>
                            {article.author && <p className="mb-1">Author: {article.author}</p>}
                            {article.category && <p className="mb-1">Category: {article.category}</p>}
                            <p className="mb-1">Published at: {new Date(article.published_at).toLocaleString()}</p>
                        </div>
                    ))}
                </div>
            ) : (
                <div className="alert alert-info">No articles found.</div>
            )}
        </div>
    );
}

export default ArticleList;