#!/usr/bin/python3


from urllib.request import Request, urlopen
from bs4 import BeautifulSoup
import mysql.connector

INSERT_ANIME_INFO = \
	"INSERT INTO animes \
	(nome, link, imagem, total, manga_canon, mixed_canon, filler) \
	VALUES (%s, %s, %s, %s, %s, %s, %s)"

UPDATE_ANIME_INFO = \
	"UPDATE animes \
	SET nome = %s, total = %s, manga_canon = %s, \
	mixed_canon = %s, filler = %s WHERE link = %s"


def url_to_soup(url):
	html = urlopen(Request (
		url, 
		headers={'User-Agent': 'Mozilla/5.0'}
	)).read()

	return BeautifulSoup(html, 'html.parser')



# If you pass path as '~/image', it will save as image.ext in your home directory, where 'ext' is the identified extension of the image
def save_img(url, path):
	# just finds out the extension
	img_ext = url.split('/')[-1].split('.')[-1].split('?')[0]
	
	img_bytes = urlopen(Request (
		url,
		headers = {'User-Agent': 'Mozilla/5.0'}
	)).read()

	f = open(f'{path}.{img_ext}', 'wb')
	f.write(img_bytes)
	f.close()



''' 1. Get all show names and links '''
url = 'https://www.animefillerlist.com'
anchors = url_to_soup(url + '/shows/').select('#ShowList .Group a')


''' 2. Connect to DB '''
db = mysql.connector.connect (
	host = 'localhost',
	user = 'site',
	passwd = '',
	database = 'anime2',
	charset = 'utf8'
)
cursor = db.cursor(buffered = True)


''' 3. Enter in each <a> tag '''
for a in anchors:
	show_url = url + a['href'] 
	soup = url_to_soup(show_url)

	# Title and relative link to the current anime
	anime_title = a.text
	rel_link = a['href']
	name = rel_link.split('/')[-1]


	# Getting the image
	img_src = soup.find('img', 
		{'width': '200', 'height': '300'}
	)['src']
	save_img(img_src, 'img/' + name)

	# Total number of episodes
	eps = len(soup.select('.EpisodeList td.Number'))

	# if for some reason the anime is listed, but has no episode
	if not eps: continue 

	# Respectively: 0% filler, some % filler, 100% filler
	canon_eps  = len(soup.select('.EpisodeList .manga_canon .Number'))
	mixed_eps  = len(soup.select('.EpisodeList .mixed_canon/filler .Number'))
	filler_eps = len(soup.select('.EpisodeList .filler .Number'))



	''' 4. Inserts if doesn't exists '''
	sql = INSERT_ANIME_INFO
	val = (anime_title, rel_link, img_src, eps, canon_eps, mixed_eps, filler_eps)

	print(f'Trying to insert [{name}]... ', end='')
	try:
		cursor.execute(sql, val)

	except mysql.connector.errors.IntegrityError:
		print(f'\nAlready exists. Updating data... ', end='')
		sql = UPDATE_ANIME_INFO
		val = (anime_title, eps, canon_eps, mixed_eps, filler_eps, rel_link)
		cursor.execute(sql, val)

	db.commit()

	print('Done!')
	print()