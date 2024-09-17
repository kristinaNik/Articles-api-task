import React, {useState} from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import ArticleList from '../components/ArticleList';
import Search from '../components/Search';


function App() {
    const [searchCriteria, setSearchCriteria] = useState({});

    const handleSearch = (criteria) => {
        setSearchCriteria(criteria);
    };

    return (
        <Router>
            <div className="container">
                <header>
                    <h1>Welcome to the Articles App</h1>
                </header>
                <main>
                    <Search onSearch={handleSearch} />
                    <Routes>
                        <Route path="/" element={<ArticleList searchCriteria={searchCriteria} />} />
                    </Routes>
                </main>
            </div>
        </Router>
    );
}

export default App;