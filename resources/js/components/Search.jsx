import React, { useState, useEffect } from 'react';
import axios from 'axios';

function Search({ onSearch }) {
    const [title, setTitle] = useState('');
    const [author, setAuthor] = useState('');
    const [category, setCategory] = useState('');
    const [source, setSource] = useState('');
    const [sources, setSources] = useState([]);

    useEffect(() => {
        const fetchSources = async () => {
            try {
                const response = await axios.get('http://localhost:8000/api/articles/source');
                setSources(response.data.source); // Use the "source" key
            } catch (error) {
                console.error('Error fetching sources:', error);
            }
        };

        fetchSources();
    }, []);

    const handleSearch = () => {
        onSearch({ title, author, category, source });
    };

    const handleReset = () => {
        setTitle('');
        setAuthor('');
        setCategory('');
        setSource('');
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
                <select
                    value={source}
                    onChange={(e) => setSource(e.target.value)}
                    className="form-control"
                >
                    <option value="">All Sources</option>
                    {sources.map((src, index) => (
                        <option key={index} value={src}>
                            {src}
                        </option>
                    ))}
                </select>
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