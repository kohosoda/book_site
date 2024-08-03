import React, { useState, useEffect } from "react";
import { useParams, Link } from "react-router-dom";

const BookDetails = () => {
    const [book, setBook] = useState(null);
    const [similarBooks, setSimilarBooks] = useState([]);
    const { id } = useParams();

    useEffect(() => {
        const fetchBookDetails = async () => {
            const response = await fetch(`/api/books/${id}`);
            const newBook = await response.json();

            const raws = 10;
            const responseForSimilar = await fetch(`/api/books/${id}/recommendations?raws=${raws}`);
            const dataForSimilar = await responseForSimilar.json();
            const newSimilarBooks = dataForSimilar.status === 'success' ? dataForSimilar.books : [];

            setBook(newBook);
            setSimilarBooks(newSimilarBooks);
        };

        fetchBookDetails();
    }, [id]);

    if (!book) return <div className="spinner-border" role="status"><span className="visually-hidden">Loading...</span></div>;

    return (
        <div className="container mt-4">
            <h2 className="mb-3">{book.title}</h2>
            <p className="mb-2"><strong>著者:</strong> {book.authors.join(", ")}</p>
            <p className="mb-2"><strong>出版年:</strong> {book.publishedDate.date}</p>
            <p className="mb-1"><strong>説明:</strong></p>
            <p className="mb-4">{book.description}</p>

            <h3>似ている本</h3>
            <ul>
                {similarBooks.map((similarBook) => (
                    <li key={similarBook.id} className="mb-2">
                        <Link to={`/books/${similarBook.id}`}>
                            {similarBook.title}
                        </Link>
                    </li>
                ))}
            </ul>

            <Link to="/" className="btn btn-primary">
                検索結果に戻る
            </Link>
        </div>
    );
};

export default BookDetails;
