import React from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import ArticleList from '../components/ArticleList';


function App() {
    return (
        <Router>
            <div className="container">
                <header>
                    <h1>Welcome to the Articles App</h1>
                </header>
                <main>
                    <Routes>
                        <Route path="/" element={<ArticleList />} />
                    </Routes>
                </main>
            </div>
        </Router>
    );
}

export default App;