import './Intro.css';
import Arrow from './Arrow.jsx';

export default function Intro() {


    return (
        <section className="section intro-section">
            <img className="intro-image" src="/norm_wall.png" alt="wall" />
            <h1 className="intro-title">AIESEC в Екб: Даём пинка под зад в твоё лучшее будущее!</h1>
            <Arrow />

            <img className="bear" src="/bear_graffiti.png" alt="bear" />
        </section>
    )
}
