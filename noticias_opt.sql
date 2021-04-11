<<<<<<< HEAD
-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-04-2021 a las 20:34:45
-- Versión del servidor: 10.4.16-MariaDB
-- Versión de PHP: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `noticias_opt`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `canales`
--

CREATE TABLE `canales` (
  `IdCanal` int(11) NOT NULL,
  `URL` text NOT NULL,
  `NombreCanal` text NOT NULL,
  `SiteImg` text NOT NULL,
  `Feed` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `canales`
--

INSERT INTO `canales` (`IdCanal`, `URL`, `NombreCanal`, `SiteImg`, `Feed`) VALUES
(1, 'https://www.thecipherbrief.com', 'The Cipher Brief', 'https://www.thecipherbrief.com/wp-content/uploads/2017/09/cropped-cropped-New-Project-32x32.png', 'https://www.thecipherbrief.com/feed'),
(2, 'https://www.cnn.com/world/index.html', 'CNN.com - RSS Channel - World', 'http://i2.cdn.turner.com/cnn/2015/images/09/24/cnn.digital.png', 'http://rss.cnn.com/rss/edition_world.rss'),
(3, 'https://www.bbc.co.uk/news/', 'BBC News - World', 'https://news.bbcimg.co.uk/nol/shared/img/bbc_news_120x60.gif', 'http://feeds.bbci.co.uk/news/world/rss.xml'),
(4, 'https://newzitnews.com', 'National News – NewzitNews.com', 'https://newzitnews.com/wp-content/uploads/2020/09/cropped-newzicon-32x32.png', 'https://newzitnews.com/feed'),
(5, 'https://justworldeducational.org', 'Blog | Just World Educational', 'https://justworldeducational.org/wp-content/uploads/2016/08/cropped-Asset-1-32x32.jpg', 'https://justworldeducational.org/category/blog/feed/');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `IdCategoria` int(11) NOT NULL,
  `NombreCategoria` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`IdCategoria`, `NombreCategoria`) VALUES
(1, 'Uncategorized'),
(2, 'All Hell Breaking Loose'),
(3, 'Climate'),
(4, 'DoD'),
(5, 'Kristin Wood'),
(6, 'Michael Klare'),
(7, 'national security'),
(8, 'Pentagon'),
(9, 'The Cipher Brief'),
(10, 'Cyber'),
(11, 'Russia'),
(12, 'cyber'),
(13, 'Paul Kolbe'),
(14, 'SolarWinds'),
(15, 'Intelligence'),
(16, 'International'),
(17, 'Iran'),
(18, 'Middle East'),
(19, 'Saudi Arabia'),
(20, 'Yemen'),
(21, 'Cyber Initiatives Group'),
(22, 'criminals'),
(23, 'Cybersecurity'),
(24, 'GCHQ'),
(25, 'Technology'),
(26, 'outage'),
(27, 'Daily News'),
(28, 'let it out'),
(29, 'good health'),
(30, 'Anti-imperialism'),
(31, 'Antiwar'),
(32, 'Blog'),
(33, 'U.S. politics'),
(34, 'Vietnam War'),
(35, 'Gaza'),
(36, 'Humanitarian affairs'),
(37, 'Israel'),
(38, 'Palestine'),
(39, 'Syria'),
(40, 'U.K. policy'),
(41, 'U.S. policy'),
(42, 'International law');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorizacion`
--

CREATE TABLE `categorizacion` (
  `IdRelacion` int(11) NOT NULL,
  `IdNoticia` int(11) NOT NULL,
  `IdCategoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `categorizacion`
--

INSERT INTO `categorizacion` (`IdRelacion`, `IdNoticia`, `IdCategoria`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 1, 8),
(9, 1, 9),
(10, 2, 10),
(11, 2, 7),
(12, 2, 11),
(13, 2, 12),
(14, 2, 13),
(15, 2, 14),
(16, 2, 9),
(17, 3, 15),
(18, 3, 16),
(19, 3, 17),
(20, 3, 18),
(21, 3, 7),
(22, 3, 19),
(23, 3, 20),
(24, 4, 10),
(25, 4, 21),
(26, 4, 7),
(27, 4, 1),
(28, 4, 22),
(29, 4, 12),
(30, 4, 23),
(31, 4, 24),
(32, 4, 9),
(33, 9, 25),
(34, 9, 26),
(35, 10, 27),
(36, 10, 28),
(37, 11, 27),
(38, 11, 29),
(39, 12, 27),
(40, 13, 30),
(41, 13, 31),
(42, 13, 32),
(43, 13, 33),
(44, 13, 34),
(45, 14, 32),
(46, 14, 35),
(47, 14, 36),
(48, 14, 37),
(49, 14, 38),
(50, 15, 31),
(51, 15, 32),
(52, 15, 39),
(53, 15, 40),
(54, 15, 41),
(55, 16, 32),
(56, 16, 35),
(57, 16, 42),
(58, 16, 37),
(59, 16, 38);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items`
--

CREATE TABLE `items` (
  `IdNoticia` int(11) NOT NULL,
  `IdCanal` int(11) NOT NULL,
  `Titulo` text NOT NULL,
  `itemLink` text NOT NULL,
  `Descripcion` text NOT NULL,
  `Fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `items`
--

INSERT INTO `items` (`IdNoticia`, `IdCanal`, `Titulo`, `itemLink`, `Descripcion`, `Fecha`) VALUES
(1, 1, 'The Pentagon’s ‘All Hell Breaking Loose’ Scenario for Climate Change', 'https://www.thecipherbrief.com/the-pentagons-all-hell-breaking-loose-scenario-for-climate-change', 'The Pentagon often wargames future scenarios for when &#8216;all hell breaks loose&#8217; with the idea of training for the worst-case scenario.  Secretary of Defense Lloyd Austin made clear when he took the helm at the Pentagon earlier this year, that &#8211; consistent with President Joe Biden’s focus on climate change &#8211; the DOD would include &#8230; \nContinue reading &#34;The Pentagon&#8217;s &#8216;All Hell Breaking Loose&#8217; Scenario for Climate Change&#34;\nThe post The Pentagon&#8217;s &#8216;All Hell Breaking Loose&#8217; Scenario for Climate Change appeared first on The Cipher Brief.\n', '2021-04-07'),
(2, 1, 'The U.S. Needs a Stronger Cyber Defense Against Russia', 'https://www.thecipherbrief.com/the-u-s-needs-a-stronger-cyber-defense-against-russia', 'Paul Kolbe, Director, Intelligence Project, Harvard University&#8217;s Belfer Center for Science and International Affairs Paul Kolbe is Director of The Intelligence Project at Harvard University’s Belfer Center for Science and International Affairs. He previously served 25 years as an operations officer in the CIA and was a member of the Senior Intelligence Service, serving in &#8230; \nContinue reading &#34;The U.S. Needs a Stronger Cyber Defense Against Russia&#34;\nThe post The U.S. Needs a Stronger Cyber Defense Against Russia appeared first on The Cipher Brief.\n', '2021-04-06'),
(3, 1, 'Where Are Six Years of War in Yemen Going?', 'https://www.thecipherbrief.com/where-are-six-years-of-war-in-yemen-going', '&#8220;The Cipher Brief has become the most popular outlet for former intelligence officers; no media outlet is even a close second to The Cipher Brief in terms of the number of articles published by formers.” &#8211; Sept. 2018, Studies in Intelligence, Vol. 62 No. Access all of The Cipher Brief&#8217;s national-security focused expert insight by becoming &#8230; \nContinue reading &#34;Where Are Six Years of War in Yemen Going?&#34;\nThe post Where Are Six Years of War in Yemen Going? appeared first on The Cipher Brief.\n', '2021-03-29'),
(4, 1, 'Why Cyber Criminals are Winning', 'https://www.thecipherbrief.com/why-cyber-criminals-are-winning', 'Cipher Brief Expert Alex Cresswell led an operational division of GCHQ and served in the Cabinet Office, directing the team of analysts (the Joint Intelligence Organisation) which provides the British Prime Minister’s daily briefing and strategic assessments for the NSC.  EXPERT PERSPECTIVE – On 17 February 2021, the DOJ and the FBI finally brought charges against three North &#8230; \nContinue reading &#34;Why Cyber Criminals are Winning&#34;\nThe post Why Cyber Criminals are Winning appeared first on The Cipher Brief.\n', '2021-03-25'),
(5, 2, '21 miners trapped by underground flood ', 'http://rss.cnn.com/~r/rss/edition_world/~3/ZYtPmA3cGE4/index.html', 'Eight miners have been rescued and 21 remain trapped in a coal mine that flooded in northwest China&#39;s Xinjiang region, Chinese state news agency Xinhua reported on Sunday, citing a local emergency department.', '2021-04-11'),
(6, 2, 'CNN showed a Myanmar military spokesman evidence of soldiers shooting children. This is what he said', 'http://rss.cnn.com/~r/rss/edition_world/~3/L-6Tvl7Zg5w/index.html', '&#34;This is not a coup,&#34; said Maj. Gen. Zaw Min Tun from a gilded hall in Myanmar&#39;s purpose-built capital Naypyidaw, the city where his comrades recently ousted an elected government, detained the country&#39;s leadership, and installed a military junta.', '2021-04-09'),
(7, 2, 'Tributes to Prince Philip pour in from around the world', 'http://rss.cnn.com/~r/rss/edition_world/~3/RvInSk33sLg/world-reacts-prince-philip-death-lon-orig-bks.cnn', 'World leaders, politicians and celebrities have sent their condolences after the announcement of Prince Philip&#39;s death.', '2021-04-09'),
(8, 3, 'Cambodia criticises edited photos of Khmer Rouge victims', 'https://www.bbc.co.uk/news/world-asia-56707984', 'Some of the images colourised by an Irish artist appear to have been edited to show them smiling.', '2021-04-11'),
(9, 4, 'Facebook Back Up', 'https://newzitnews.com/facebook-back-up/', 'Facebook Back Up. Facebook Inc’s platforms including WhatsApp, Messenger and Instagram were down for thousands of users on Thursday,...\nThe post Facebook Back Up appeared first on National News - NewzitNews.com.\n', '2021-04-10'),
(10, 4, 'Reveal The Truth', 'https://newzitnews.com/reveal-the-truth/', 'Reveal The Truth. Are you ready for the great reveal? I mean, really ready? We have been waiting, waiting, waiting for the arrests to be made\nThe post Reveal The Truth appeared first on National News - NewzitNews.com.\n', '2021-04-08'),
(11, 4, 'Your Body and Soul', 'https://newzitnews.com/your-body-and-soul/', 'Your Body and Soul. The Vaccine is not about protecting our good Health. It is about owning our bodies and souls. The viral vector/mRNA new...\nThe post Your Body and Soul appeared first on National News - NewzitNews.com.\n', '2021-04-07'),
(12, 4, 'Happy Easter Sunday!', 'https://newzitnews.com/happy-easter-sunday/', 'Happy Easter Sunday!\nThe post Happy Easter Sunday! appeared first on National News - NewzitNews.com.\n', '2021-04-04'),
(13, 5, 'People Make the Peace: Remembering Rennie Davis', 'https://justworldeducational.org/2021/04/people-make-the-peace-remembering-rennie-davis/', 'by Karín Aguilar-San Juan and Frank Joyce co-editors, The People Make the Peace: Lessons from the Vietnam Antiwar Movement Many people equate Rennie Davis with the Vietnam antiwar movement that ... \nRead More\nThe post People Make the Peace: Remembering Rennie Davis appeared first on Just World Educational.\n', '2021-04-08'),
(14, 5, 'JVP-HAC: Covid 19 Timeline March 28-April 3, 2021', 'https://justworldeducational.org/2021/04/jvp-hac-covid-19-timeline-march-28-april-3-2021/', 'by Alice Rothchild, MD We’re pleased to repost this weekly report by JWE board member Alice Rothchild, MD, which was earlier posted by the Jewish Voice for Peace Health Advisory ... \nRead More\nThe post JVP-HAC: Covid 19 Timeline March 28-April 3, 2021 appeared first on Just World Educational.\n', '2021-04-06'),
(15, 5, 'Two Ambassadors to Syria With Wildly Different Analyses', 'https://justworldeducational.org/2021/04/two-ambassadors-to-syria-with-wildly-different-analyses/', 'by Rick Sterling We&#8217;re pleased to cross-post&#160;this piece from JWE Board Member Rick Sterling which was first published on AntiWar.Blog &#38; La Progressive Former Ambassador Peter Ford was one of ... \nRead More\nThe post Two Ambassadors to Syria With Wildly Different Analyses appeared first on Just World Educational.\n', '2021-04-06'),
(16, 5, 'How We Speak About the Failure of the PLO', 'https://justworldeducational.org/2021/04/how-we-speak-about-the-failure-of-the-plo/', 'by Helena Cobban We&#8217;re pleased to crosspost this piece from JWE CEO Helena Cobban which was first featured in the Boston Review It is hard to believe that it has been ... \nRead More\nThe post How We Speak About the Failure of the PLO appeared first on Just World Educational.\n', '2021-04-01');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `canales`
--
ALTER TABLE `canales`
  ADD PRIMARY KEY (`IdCanal`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`IdCategoria`);

--
-- Indices de la tabla `categorizacion`
--
ALTER TABLE `categorizacion`
  ADD PRIMARY KEY (`IdRelacion`);

--
-- Indices de la tabla `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`IdNoticia`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `canales`
--
ALTER TABLE `canales`
  MODIFY `IdCanal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `IdCategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `categorizacion`
--
ALTER TABLE `categorizacion`
  MODIFY `IdRelacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT de la tabla `items`
--
ALTER TABLE `items`
  MODIFY `IdNoticia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
=======
-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-04-2021 a las 17:30:36
-- Versión del servidor: 10.4.14-MariaDB
-- Versión de PHP: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `noticias_opt`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `canales`
--

CREATE TABLE `canales` (
  `IdCanal` int(11) NOT NULL,
  `URL` text NOT NULL,
  `NombreCanal` text NOT NULL,
  `SiteImg` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `IdCategoria` int(11) NOT NULL,
  `NombreCategoria` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorizacion`
--

CREATE TABLE `categorizacion` (
  `IdRelacion` int(11) NOT NULL,
  `IdNoticia` int(11) NOT NULL,
  `IdCategoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items`
--

CREATE TABLE `items` (
  `IdNoticia` int(11) NOT NULL,
  `IdCanal` int(11) NOT NULL,
  `Titulo` text NOT NULL,
  `itemLink` text NOT NULL,
  `Descripcion` text NOT NULL,
  `Fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `canales`
--
ALTER TABLE `canales`
  ADD PRIMARY KEY (`IdCanal`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`IdCategoria`);

--
-- Indices de la tabla `categorizacion`
--
ALTER TABLE `categorizacion`
  ADD PRIMARY KEY (`IdRelacion`);

--
-- Indices de la tabla `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`IdNoticia`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `canales`
--
ALTER TABLE `canales`
  MODIFY `IdCanal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `IdCategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `categorizacion`
--
ALTER TABLE `categorizacion`
  MODIFY `IdRelacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `items`
--
ALTER TABLE `items`
  MODIFY `IdNoticia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
>>>>>>> b4e8bc1223e4ac0430a7374acdae36d508f4996f
