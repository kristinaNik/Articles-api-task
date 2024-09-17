import React, { useState } from 'react';

function Search({ onSearch }) {
    const [title, setTitle] = useState('');
    const [author, setAuthor] = useState('');
    const [category, setCategory] = useState('');
    const [source, setSource] = useState('');
    const [publishedAt, setPublishedAt] = useState('');

    const handleSearch = () => {
        onSearch({ title, author, category, source, published_at: publishedAt });
    };

    const handleReset = () => {
        setTitle('');
        setAuthor('');
        setCategory('');
        setSource('');
        setPublishedAt('');
        onSearch({});
    };

    return (
        <div className="search-form">
            <h2>Search Articles</h2>
            <div className="form-group">
                <label>Title:</label>
                <input
                    type="text"
                    value={title}
                    onChange={(e) => setTitle(e.target.value)}
                    className="form-control"
                />
            </div>
            <div className="form-group">
                <label>Author:</label>
                <input
                    type="text"
                    value={author}
                    onChange={(e) => setAuthor(e.target.value)}
                    className="form-control"
                />
            </div>
            <div className="form-group">
                <label>Category:</label>
                <input
                    type="text"
                    value={category}
                    onChange={(e) => setCategory(e.target.value)}
                    className="form-control"
                />
            </div>
            <div className="form-group">
                <label>Source:</label>
                <input
                    type="text"
                    value={source}
                    onChange={(e) => setSource(e.target.value)}
                    className="form-control"
                />
            </div>
            <div className="form-group">
                <label>Published Date:</label>
                <input
                    type="date"
                    value={publishedAt}
                    onChange={(e) => setPublishedAt(e.target.value)}
                    className="form-control"
                />
            </div>
            <button onClick={handleSearch} className="btn btn-primary">
                Search
            </button>
            <button onClick={handleReset} className="btn btn-secondary ml-2">
                Reset
            </button>
        </div>
    );
}

export default Search;