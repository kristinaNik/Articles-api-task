import React, { useState } from 'react';
import axios from 'axios';

function Search() {
    const [title, setTitle] = useState('');
    const [author, setAuthor] = useState('');
    const [category, setCategory] = useState('');
    const [results, setResults] = useState([]);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);

    const handleSearch = async () => {
        // Clear previous results before performing a new search
        setResults([]);
        setLoading(true);
        setError(null);

        try {
            const response = await axios.get(`http://localhost:8000/api/articles/search`, {
                params: {
                    title: title.trim(),
                    author: author.trim(),
                    category: category.trim(),
                },
            });
            setResults(response.data.data);  // Update results with new data
        } catch (error) {
            setError('Search failed. Please try again.');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div>
            <h2>Search Articles</h2>
            <div style={{ display: 'flex', flexDirection: 'column', gap: '10px', maxWidth: '400px', margin: 'auto' }}>
                <input
                    type="text"
                    value={title}
                    onChange={(e) => setTitle(e.target.value)}
                    placeholder="Search by title"
                    style={{ padding: '10px', fontSize: '16px' }}
                />
                <input
                    type="text"
                    value={author}
                    onChange={(e) => setAuthor(e.target.value)}
                    placeholder="Search by author"
                    style={{ padding: '10px', fontSize: '16px' }}
                />
                <input
                    type="text"
                    value={category}
                    onChange={(e) => setCategory(e.target.value)}
                    placeholder="Search by category"
                    style={{ padding: '10px', fontSize: '16px' }}
                />
                <button onClick={handleSearch} style={{ padding: '10px', fontSize: '16px' }}>
                    Search
                </button>
            </div>

            {loading && <p>Loading...</p>}
            {error && <p>{error}</p>}

            <ul style={{ marginTop: '20px' }}>
                {results.length > 0 ? (
                    results.map(article => (
                        <li key={article.id} style={{ marginBottom: '20px' }}>
                            <a href={`/articles/${article.id}`} style={{ fontSize: '18px', fontWeight: 'bold' }}>
                                {article.title}
                            </a>
                            <p>Author: {article.author || 'Unknown'}</p>
                            <p>Category: {article.category || 'Uncategorized'}</p>
                        </li>
                    ))
                ) : (
                    <p>No articles found.</p>
                )}
            </ul>
        </div>
    );
}

export default Search;