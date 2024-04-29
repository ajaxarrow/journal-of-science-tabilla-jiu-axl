<?php
class Volume_model extends CI_Model{

	public function fetch_volume($query = NULL) {
    if (!is_null($query)) {
        $this->db->like('vol_name', $query);
        $this->db->or_like('vol_description', $query);
    }

    $query = $this->db->get('volume');
    $volumes = $query->result_array();

    return $volumes;
}


	public function get_volume() {

			$query = $this->db->get('volume');
			$volumes = $query->result_array();


		foreach ($volumes as &$volume) {
			$volume['articles'] = $this->get_articles_by_volume_id($volume['vol_id']);
		}

		return $volumes;
	}

	public function get_archived_volumes() {
		$this->db->where('archived', 1);
		$this->db->where('published', 1);
		$query = $this->db->get('volume');
		$volumes = $query->result_array();

		foreach ($volumes as &$volume) {
			$volume['articles'] = $this->get_articles_by_volume_id($volume['vol_id']);
		}

		return $volumes;
	}


	public function get_volume_by_id($id) {
    $volume = $this->db->get_where('volume', array('vol_id' => $id))->row_array();

    if ($volume) {
        $volume['articles'] = $this->get_articles_by_volume_id($volume['vol_id']);
    }

    return $volume;
	}

	// public function get_volume_by_id($id) {

	// 	$volumes = $this->db->get_where('volume', array('vol_id' => $id))->result_array();

	// 	foreach ($volumes as &$volume) {
	// 		$volume['articles'] = $this->get_articles_by_volume_id($volume['vol_id']);
	// 	}
	// 	return $volumes;
	// }



	public function get_articles_by_volume_id($id){
		$this->db->select('authors.*, articles.*');
		$this->db->from('article_author');
		$this->db->join('articles', 'article_author.article_id = articles.article_id', 'inner');
		$this->db->join('authors', 'article_author.authid = authors.author_id', 'inner');
		$this->db->order_by('articles.date_published', 'DESC');
		$this->db->where('articles.volumeid', $id);

		$query = $this->db->get();
		return $query->result_array();
	}

	public function add_volume($data){
		$this->db->insert('volume', $data);
	}

	public function update_volume($id, $data){
		$this->db->where('vol_id', $id);
		$this->db->update('volume', $data);
	}

	public function delete_volume($id){
			$this->db->where('vol_id', $id);
			$this->db->delete('volume');
	}
}
