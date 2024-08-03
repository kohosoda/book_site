import React from 'react';
import { Link } from 'react-router-dom';

const BookList = ({ books }) => {
    return (
        <div className="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            {books.map((book) => (
                <div key={book.id} className="col">
                    <div className="card h-100">
                        <div className="card-body">
                            <h5 className="card-title">{book.title}</h5>
                            <p className="card-text">{book.author}</p>
                            <Link to={`/books/${book.id}`} className="btn btn-outline-primary">
                                詳細を見る
                            </Link>
                        </div>
                    </div>
                </div>
            ))}
        </div>
    );
}

export default BookList;
